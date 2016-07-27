<?php namespace Argentum\LibreDTE;

use Argentum\Common\AbstractGateway;

/**
 * LibreDTE Gateway
 *
 * The gateway uses the LibreDTE service for signing electronic documents in Chile
 * 
 * @see \Argentum\Common\AbstractGateway
 * @link http://www.sii.cl/factura_electronica/index.html
 */
class Gateway extends AbstractGateway
{
    const ERROR_INVALID_USER = 'FM501';
    const ERROR_MISMATCHED_RFC = 'FM507';
    const ERROR_UNLISTED_RFC = 'FM402';
    const ERROR_INVALID_CFDI = '301';
    const ERROR_INVALID_STAMP = '302';
    const ERROR_INVALID_CERTIFICATE_NUMBER = '303';
    const ERROR_REVOKED_CERTIFICATE = '304';
    const ERROR_EXPIRED_CERTIFICATE = '305';
    const ERROR_WRONG_SIGNED_CFDI = '306';
    const ERROR_SIGN_INCLUDED_CFDI = '307';
    const ERROR_ALREADY_SIGNED_CFDI = '307';
    const ERROR_WRONG_DATED_CFDI = '401';

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'LibreDTE';
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultParameters()
    {
        return array(
            'testMode'  => false,
            'apiUrl'    => 'https://libredte.cl',
            'apiHash'   => '',
        );
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
     * Sign document
     *
     * @param array $parameters
     * @return Message\Response
     */
    public function sign(array $parameters = array())
    {
        return $this->createRequest('\Argentum\LibreDTE\Message\SignRequest', $parameters);
    }
}
