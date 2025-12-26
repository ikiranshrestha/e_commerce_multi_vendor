<?php

namespace App\Exceptions\Api;

use Exception;

abstract class BaseApiException extends Exception
{
    protected int $statusCode = 400;
    protected ?array $errors = null;

    public function __construct(string $message, ?array $errors = null)
    {
        parent::__construct($message);
        $this->errors = $errors;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }
}
