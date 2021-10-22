<?php

namespace EPhone\Http\Response;

class LoginResponse extends Response
{
    private $token = '';

    public function resolve()
    {
        parent::resolve();
        $this->token = $this->body['token'] ?? '';
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}