<?php

namespace Checkdomain\TeleCash\IPG\API\Model;

/**
 * Class CreditCardItem
 *
 * @package Checkdomain\TeleCash\IPG\API\Model
 */
class CreditCardItem extends DataStorageItem
{

    /** @var CreditCardData */
    protected $creditCardData;

    /**
     * @param CreditCardData $creditCardData
     * @param string         $hostedDataId
     * @param null|string    $function
     * @param null|string    $declineHostedDataDuplicates
     */
    public function __construct(CreditCardData $creditCardData, $hostedDataId, $function = null, $declineHostedDataDuplicates = null)
    {
        parent::__construct($hostedDataId, $function, $declineHostedDataDuplicates);

        $this->creditCardData = $creditCardData;
    }

    /**
     * @param \DOMDocument $document
     *
     * @return \DOMElement
     */
    public function getXML(\DOMDocument $document)
    {
        $xml = $document->createElement('ns2:DataStorageItem');

        $ccData = $this->creditCardData->getXML($document);
        $dataId = $document->createElement('ns2:HostedDataID');
        $dataId->textContent = $this->hostedDataId;

        if ($this->function != null) {
            $function = $document->createElement('ns2:Function');
            $function->textContent = $this->function;
            $xml->appendChild($function);
        }
        if ($this->declineHostedDataDuplicates != null) {
            $declineDuplicates = $document->createElement('ns2:DeclineHostedDataDuplicates');
            $declineDuplicates->textContent = $this->declineHostedDataDuplicates;
            $xml->appendChild($declineDuplicates);
        }

        $xml->appendChild($ccData);
        $xml->appendChild($dataId);

        return $xml;
    }
}
