<?php

namespace Checkdomain\TeleCash\IPG\API\Response\Action;

use Checkdomain\TeleCash\IPG\API\AbstractResponse;

/**
 * Class AbstractActionResponse
 *
 * @package Checkdomain\TeleCash\IPG\API\Action
 */
abstract class AbstractActionResponse extends AbstractResponse
{

    /**
     * @var bool $wasSuccessful
     */
    protected $wasSuccessful = false;

    /**
     * @var string $errorMessage
     */
    protected $errorMessage = '';

    /**
     * @return bool
     */
    public function wasSuccessful()
    {
        return $this->wasSuccessful;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}
