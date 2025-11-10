<?php

namespace Nhanchaukp\TempmailEdu\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface;

class ApiException extends Exception
{
    private ?ResponseInterface $response;

    public function __construct(string $message = "", int $code = 0, ?ResponseInterface $response = null, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->response = $response;
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}