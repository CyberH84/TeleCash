<?php

namespace Checkdomain\TeleCash\IPG\API\Model;

/**
 * Class Payment
 */
class Payment implements ElementInterface
{

    const CURRENCY_EUR = "978";

    /** @var string $hostedDataId */
    private $hostedDataId;
    /** @var float $amount */
    private $amount;

    /**
     * @param string|null $hostedDataId
     * @param float|null  $amount
     */
    public function __construct($hostedDataId = null, $amount = null)
    {
        $this->hostedDataId = $hostedDataId;
        $this->amount       = $amount;
    }

    /**
     * @param \DOMDocument $document
     *
     * @return \DOMElement
     */
    public function getXML(\DOMDocument $document)
    {
        $xml = $document->createElement('ns1:Payment');

        if (!empty($this->hostedDataId)) {
            $hostedDataId = $document->createElement('ns1:HostedDataID');
            $hostedDataId->textContent = $this->hostedDataId;

            $xml->appendChild($hostedDataId);
        }

        if (!empty($this->amount)) {
            $amount                = $document->createElement('ns1:ChargeTotal');
            $amount->textContent   = $this->amount;
            $currency              = $document->createElement('ns1:Currency');
            $currency->textContent = self::CURRENCY_EUR;

            $xml->appendChild($amount);
            $xml->appendChild($currency);
        }

        return $xml;
    }
}
