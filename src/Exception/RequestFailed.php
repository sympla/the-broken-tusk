<?php

namespace Tracksale\Exception;

use Psr\Http\Message\ResponseInterface;

class RequestFailed extends \Exception
{
    /** @var int */
    protected $statusCode;

    /** @var ResponseInterface */
    protected $response;

    /**
     * RequestFailed constructor.
     *
     * @param string|null    $message
     * @param int            $statusCode
     * @param ResponseInterface $response
     */
    public function __construct($message, $statusCode, ResponseInterface $response)
    {
        $this->statusCode = $statusCode;
        $this->response = $response;

        parent::__construct($message, $statusCode);
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
