<?php

namespace Checkdomain\TeleCash\IPG\API\Response;

use Checkdomain\TeleCash\IPG\API\AbstractResponse;

/**
 * Class Action
 *
 * @package Checkdomain\TeleCash\IPG\API\Action
 */
abstract class Action extends AbstractResponse
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
