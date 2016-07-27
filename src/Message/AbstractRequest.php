<?php namespace Argentum\LibreDTE\Message;

use Argentum\Common\Message\AbstractRequest as CommonAbstractRequest;
use sasco\LibreDTE\LibreDTE;

/**
 * LibreDTE Abstract Request
 */
abstract class AbstractRequest extends CommonAbstractRequest
{
    public function send()
    {
        return parent::send();
    }

    /**
     * Get API url
     *
     * @return string
     */
    public function getApiUrl()
    {
        return $this->getParameter('apiUrl');
    }

    /**
     * Set API url
     *
     * @param string $value
     * @return $this
     */
    public function setApiUrl($value)
    {
        return $this->setParameter('apiUrl', $value);
    }

    /**
     * Get API hash
     *
     * @return string
     */
    public function getApiHash()
    {
        return $this->getParameter('apiHash');
    }

    /**
     * Set API hash
     *
     * @param string $value
     * @return $this
     */
    public function setApiHash($value)
    {
        return $this->setParameter('apiHash', $value);
    }

    /**
     * Get LibreDTE endpoint
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->getApiUrl();
    }

    /**
     * @param array $data
     * @return Response
     */
    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }

    abstract protected function getFunction();
}
