<?php namespace Argentum\LibreDTE\Message;

use sasco\LibreDTE\SDK\LibreDTE;

/**
 * LibreDTE Sign Request
 */
class SignRequest extends AbstractRequest
{
    /**
     * {@inheritDoc}
     */
    protected function getFunction()
    {
        return 'EnvioDTE';
    }

    /**
     * Get document to sign
     *
     * @return \Argentum\Common\Document\AbstractDocument|\Argentum\Common\Document\Ticket
     */
    public function getDocument()
    {
        return $this->getParameter('document');
    }

    /**
     * Set document to sign
     *
     * @param \Argentum\Common\Document\AbstractDocument $value
     * @return \Argentum\Common\Message\AbstractRequest
     */
    public function setDocument($value)
    {
        return $this->setParameter('document', $value);
    }

    /**
     * {@inheritDoc}
     */
    public function getData()
    {
        $this->validate();

        /** @var \Argentum\Common\Document\AbstractDocument|\Argentum\LibreDTE\Document\Invoice $document */
        $document = $this->getDocument();

        $details = [];

        /** @var \Argentum\Common\Item $item */
        foreach ($document->getItems() as $item) {
            $details[] = [
                'NmbItem' => $item->getName(),
                'QtyItem' => $item->getQuantity(),
                'PrcItem' => $item->getPrice(),
            ];
        }

        $data = [
            'Encabezado' => [
                'IdDoc' => [
                    'TipoDTE' => $document->getTypeDte(),
                ],
                'Emisor' => [
                    'RUTEmisor' => preg_replace('/[^0-9\-]/', '', $document->getFrom()->getId()),
                ],
                'Receptor' => [
                    'RUTRecep' => preg_replace('/[^0-9\-]/', '', $document->getTo()->getId()),
                    'RznSocRecep' => $document->getTo()->getName(),
                    'GiroRecep' => 'Particular',
                    'DirRecep' => $document->getFrom()->getAddress()->getAddress_1(),
                    'CmnaRecep' => $document->getFrom()->getAddress()->getLocality(),
                ],
            ],
            'Detalle' => $details,
        ];

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function sendData($data)
    {
        $client = new LibreDTE($this->getApiHash(), $this->getEndpoint());

        // Generate DTE XML
        try {
            $issueResponse = $client->post('/dte/documentos/emitir', $data);
            if ($issueResponse['status']['code'] != 200) {
                throw new \Exception('Error issuing provisional DTE: ' . $issueResponse['body'], $issueResponse['status']['code']);
            }

            $generateResponse = $client->post('/dte/documentos/generar', $issueResponse['body']);
            if ($generateResponse['status']['code'] != 200) {
                throw new \Exception('Error generating DTE: ' . $generateResponse['body'], $generateResponse['status']['code']);
            }

            $response = [
                'code' => $generateResponse['status']['code'],
                'unsigned_xml' => $issueResponse['body'],
                'xml' => $generateResponse['body']['xml'],
            ];
        } catch (\Exception $e) {
            $response = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }

        // Try to generate PDF
        if (isset($response['xml'])) {
            try {
                $pdfResponse = $client->post('/dte/documentos/generar_pdf', ['xml' => $response['xml']]);
                if ($pdfResponse['status']['code'] != 200) {
                    throw new \Exception('Error generating PDF of the DTE: ' . $pdfResponse['body']);
                }

                $response['pdf'] = $pdfResponse['body'];
            } catch (\Exception $e) {
            }
        }

        return $this->createResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    protected function createResponse($data)
    {
        return $this->response = new SignResponse($this, $data);
    }
}
