<?php

namespace Checkdomain\TeleCash\IPG\API;

/**
 * Class Request
 */
abstract class AbstractRequest
{

    /** @var \DOMDocument */
    protected $document;

    /** @var \DOMElement */
    protected $element;

    /**
     * @return \DOMDocument
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @return \DOMElement
     */
    public function getElement()
    {
        return $this->element;
    }
}
