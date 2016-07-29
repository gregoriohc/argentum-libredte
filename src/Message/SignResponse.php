<?php namespace Argentum\LibreDTE\Message;

/**
 * LibreDTE Sign Response
 */
class SignResponse extends Response
{
    /**
     * SignResponse constructor
     *
     * @param SignRequest $request
     * @param mixed $data
     */
    public function __construct(SignRequest $request, $data)
    {
        parent::__construct($request, $data);

        // Decode base64 encoded properties
        foreach (['xml', 'pdf', 'png', 'txt'] as $property) {
            if (isset($this->data[$property])) {
                $this->data[$property] = base64_decode($this->data[$property]);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isSuccessful()
    {
        return false !== $this->getReference();
    }

    /**
     * {@inheritDoc}
     */
    public function getReference()
    {
        if (isset($this->data['xml'])) {
            preg_match_all('/<Folio>(.*?)<\/Folio>/mi', $this->data['xml'], $matches);
            if (isset($matches[1][0])) {
                return $matches[1][0];
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        $files = [];

        $files[] = [
            'name'      => 'signed',
            'extension' => 'xml',
            'content'   => $this->data['xml'],
        ];

        $files[] = [
            'name'      => 'unsigned',
            'extension' => 'xml',
            'content'   => $this->data['unsigned_xml'],
        ];

        if (!empty($this->data['pdf'])) {
            $files[] = [
                'name'      => 'signed',
                'extension' => 'pdf',
                'content'   => $this->data['pdf'],
            ];
        }

        return $files;
    }


}
