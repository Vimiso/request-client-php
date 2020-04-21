<?php

namespace Vimiso\Http\Exceptions;

use Exception;
use Throwable;

class RequestException extends Exception
{
    /**
     * @var int
     */
    protected $statusCode = 0;

    /**
     * @var array
     */
    protected $response = [];

    /**
     * @param string $message
     * @param int $statusCode
     * @param array $response
     * @param null|\Throwable $previous
     * @return void
     */
    public function __construct(
        $message,
        $statusCode = 0,
        array $response = [],
        ?Throwable $previous = null
    )
    {
        parent::__construct($message, $statusCode, null);

        $this->statusCode = $statusCode;
        $this->response = $response;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $className = __CLASS__;
        $message = rtrim($this->message, "\n");

        return "{$className}: Status code: [{$this->statusCode}]"
            . " => {$message}\n{$this->getTraceAsString()}";
    }
}
