<?php

namespace Checkdomain\TeleCash\IPG\API\Model;

/**
 * Class DataStorageItem
 *
 * @package Checkdomain\TeleCash\IPG\API\Model
 */
class DataStorageItem implements ElementInterface
{
    /** @var string  */
    protected $hostedDataId;
    /** @var null|string  */
    protected $function;
    /** @var null|string  */
    protected $declineHostedDataDuplicates;


    /**
     * @param string      $hostedDataId
     * @param string|null $function
     * @param string|null $declineHostedDataDuplicates
     */
    public function __construct($hostedDataId, $function = null, $declineHostedDataDuplicates = null)
    {
        $this->hostedDataId                = $hostedDataId;
        $this->function                    = $function;
        $this->declineHostedDataDuplicates = $declineHostedDataDuplicates;
    }

    /**
     * @param \DOMDocument $document
     *
     * @return \DOMElement
     */
    public function getXML(\DOMDocument $document)
    {
        $xml = $document->createElement('ns2:DataStorageItem');

        $dataId              = $document->createElement('ns2:HostedDataID');
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

        $xml->appendChild($dataId);

        return $xml;
    }

    /**
     * @param string $function
     */
    public function setFunction($function)
    {
        $this->function = $function;
    }

}
