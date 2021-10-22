<?php

namespace EPhone\Http\Response;

class Response
{
    protected $code = '200';

    protected $body;

    protected $message;

    /**
     * @var array $header
     */
    protected $header = [];

    protected $error;

    public function resolve()
    {
        if ($this->code !== '200') {
            $this->setError($this->body);
        } else {
            if (json_decode($this->body)) {
                $this->body = json_decode($this->body, true);

                if (isset($this->body['success']) and $this->body['success'] == false) {
                    $this->setError($this->body['message']);
                    return;
                }
                if (($code = $this->body['statusCode'] ?? '500') and $code === '00000') {
                    $this->message = $this->body['message'] ?? '';
                    $this->body = $this->body['data'] ?? '';
                    $this->setCode('200');
                } else {
                    $this->setCode($code);
                    $this->setError($this->body['message']);
                }
            } else {
                $this->setError($this->body);
            }
        }
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Response
     */
    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $body
     * @return Response
     */
    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeader(): array
    {
        return $this->header;
    }

    /**
     * @param array $header
     * @return Response
     */
    public function setHeader(array $header): self
    {
        $this->header = $header;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param mixed $error
     */
    public function setError($error): self
    {
        $this->error = $error;
        if ($this->code == '200') {
            $this->code = '500';
        }
        return $this;
    }

    public function isSuccess(): bool
    {
        return $this->code == '200';
    }
}