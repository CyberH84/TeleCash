<?php

namespace Checkdomain\TeleCash\IPG\API\Service;

use Checkdomain\TeleCash\IPG\API\AbstractRequest;
use Checkdomain\TeleCash\IPG\API\Request\ActionRequest;
use Checkdomain\TeleCash\IPG\API\Request\OrderRequest;
use Checkdomain\TeleCash\IPG\API\Response\ErrorResponse;

/**
 * Class OrderService
 *
 * @package Checkdomain\TeleCash\IPG\API\Service
 */
class OrderService extends SoapClientCurl
{

    const NAMESPACE_N1   = 'http://ipg-online.com/ipgapi/schemas/v1';
    const NAMESPACE_N2   = 'http://ipg-online.com/ipgapi/schemas/a1';
    const NAMESPACE_N3   = 'http://ipg-online.com/ipgapi/schemas/ipgapi';
    const NAMESPACE_SOAP = 'http://schemas.xmlsoap.org/soap/envelope/';

    const SOAP_ERROR_SERVER = 'SOAP-ENV:Server';
    const SOAP_ERROR_CLIENT = 'SOAP-ENV:Client';

    const SOAP_CLIENT_ERROR_MERCHANT   = 'MerchantException';
    const SOAP_CLIENT_ERROR_PROCESSING = 'ProcessingException';

    /**
     * @param array  $curlOptions CURL config values
     * @param string $username    API user
     * @param string $password    API pass
     */
    public function __construct($curlOptions, $username, $password)
    {
        parent::__construct($curlOptions, $username, $password);
    }

    /**
     * @param \DOMNode $element
     */
    public function dumpDOMElement(\DOMNode $element)
    {
        var_dump($element->ownerDocument->saveXML($element));
    }

    /**
     * @param \DOMDocument $responseDoc
     *
     * @return ErrorResponse|null
     * @throws \Exception
     */
    private function checkForSoapFault(\DOMDocument $responseDoc)
    {
        return ErrorResponse::createFromSoapFault($responseDoc);
    }

    /**
     * @param AbstractRequest $payload
     *
     * @return \DOMDocument|ErrorResponse
     *
     * @throws \Exception
     */
    private function soapCall(AbstractRequest $payload)
    {
        $request = $payload->getDocument();

        $envelope = $request->createElementNS('http://schemas.xmlsoap.org/soap/envelope/', 'SOAP-ENV:Envelope');
        $envelope->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns1', self::NAMESPACE_N1);
        $envelope->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns2', self::NAMESPACE_N2);
        $envelope->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:ns3', self::NAMESPACE_N3);

        $body = $request->createElement('SOAP-ENV:Body');
        $body->appendChild($payload->getElement());
        $envelope->appendChild($body);

        $request->appendChild($envelope);
        $xml = $request->saveXML();

        var_dump($xml);
        $response = $this->doRequest($xml);
        var_dump($response);

        $responseDoc = new \DOMDocument('1.0', 'UTF-8');
        $responseDoc->loadXML($response);

        $errorResponse = $this->checkForSoapFault($responseDoc);

        return $errorResponse !== null ? $errorResponse : $responseDoc;
    }

    /**
     * @param ActionRequest $actionRequest
     *
     * @return \DOMDocument|ErrorResponse
     */
    public function IPGApiAction(ActionRequest $actionRequest)
    {
        return $this->soapCall($actionRequest);
    }


    /**
     * @param OrderRequest $orderRequest
     *
     * @return \DOMDocument|ErrorResponse
     */
    public function IPGApiOrder(OrderRequest $orderRequest)
    {
        return $this->soapCall($orderRequest);
    }

}
