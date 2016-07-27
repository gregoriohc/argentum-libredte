<?php namespace Argentum\LibreDTE\Message;

use Argentum\Common\Message\AbstractResponse;
use Argentum\Common\Message\ResponseInterface;

/**
 * LibreDTE Response
 */
class Response extends AbstractResponse implements ResponseInterface
{
    /**
     * {@inheritDoc}
     */
    public function isSuccessful()
    {
        return '200' == $this->getCode();
    }

    /**
     * {@inheritDoc}
     */
    public function getReference()
    {
        return false;
    }

    /**
     * Get response message
     *
     * @return string
     */
    public function getMessage()
    {
        return isset($this->data['message']) ? (string) $this->data['message'] : '';
    }

    /**
     * Get response status code
     *
     * @return string
     */
    public function getCode()
    {
        return isset($this->data['code']) ? (string) $this->data['code'] : '200';
    }
}