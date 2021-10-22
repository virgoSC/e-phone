<?php

namespace EPhone\Http\Response;

class SeatStatusResponse extends Response
{
    private $status = '';

    public function resolve()
    {
        parent::resolve();
        $this->status = $this->body;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}