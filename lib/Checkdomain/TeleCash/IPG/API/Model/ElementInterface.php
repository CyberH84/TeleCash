<?php

namespace Checkdomain\TeleCash\IPG\API\Model;

/**
 * Interface ElementInterface
 *
 * @package Checkdomain\TeleCash\IPG\API
 */
interface ElementInterface
{
    /**
     * @param \DOMDocument $document
     *
     * @return mixed
     */
    public function getXML(\DOMDocument $document);
}
