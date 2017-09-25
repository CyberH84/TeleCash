<?php

namespace Checkdomain\TeleCash\IPG\API\Model;

/**
 * Class RecurringPaymentInformation
 */
class RecurringPaymentInformation implements ElementInterface
{

    const PERIOD_DAY   = 'day';
    const PERIOD_WEEK  = 'week';
    const PERIOD_MONTH = 'month';
    const PERIOD_YEAR  = 'year';

    /** @var \DateTime $startDate */
    private $startDate;
    /** @var int $installmentCount */
    private $installmentCount;
    /** @var int $installmentFrequency */
    private $installmentFrequency;
    /** @var string $installmentPeriod */
    private $installmentPeriod;

    /**
     * @param \DateTime|null $startDate
     * @param int            $installmentCount
     * @param int            $installmentFrequency
     * @param string         $installmentPeriod
     */
    public function __construct($startDate, $installmentCount, $installmentFrequency, $installmentPeriod)
    {
        $this->startDate            = $startDate;
        $this->installmentCount     = $installmentCount;
        $this->installmentFrequency = $installmentFrequency;
        $this->installmentPeriod    = $installmentPeriod;
    }

    /**
     * @param \DOMDocument $document
     *
     * @return \DOMElement
     */
    public function getXML(\DOMDocument $document)
    {
        $xml = $document->createElement('ns2:RecurringPaymentInformation');

        if ($this->startDate !== null) {
            $startDate              = $document->createElement('ns2:RecurringStartDate');
            $startDate->textContent = $this->startDate->format('Ymd');
            $xml->appendChild($startDate);
        }

        if ($this->installmentCount !== null) {
            $installmentCount              = $document->createElement('ns2:InstallmentCount');
            $installmentCount->textContent = $this->installmentCount;
            $xml->appendChild($installmentCount);
        }

        if ($this->installmentFrequency !== null) {
            $installmentFrequency              = $document->createElement('ns2:InstallmentFrequency');
            $installmentFrequency->textContent = $this->installmentFrequency;
            $xml->appendChild($installmentFrequency);
        }

        if ($this->installmentFrequency !== null) {
            $installmentPeriod              = $document->createElement('ns2:InstallmentPeriod');
            $installmentPeriod->textContent = $this->installmentPeriod;
            $xml->appendChild($installmentPeriod);
        }

        return $xml;
    }
}
