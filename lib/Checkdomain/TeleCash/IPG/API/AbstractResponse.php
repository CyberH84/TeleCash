<?php

namespace Checkdomain\TeleCash\IPG\API;

/**
 * Class AbstractResponse
 */
abstract class AbstractResponse
{

    protected function firstElementByTagNSString(\DOMDocument $doc, $namespace, $tagName)
    {
        $elements = $doc->getElementsByTagNameNS($namespace, $tagName);

        if ($elements->length > 0) {
            return $elements->item(0)->nodeValue;
        }

        throw new \Exception("Tag " .$namespace . ':' . $tagName . " not found");
    }
}
