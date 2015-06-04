<?php

namespace Checkdomain\TeleCash\IPG\API\Model;

/**
 * Interface ElementInterface
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
