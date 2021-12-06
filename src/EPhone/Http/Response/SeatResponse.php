<?php

namespace EPhone\Http\Response;

class SeatResponse extends Response
{
    private $seatNo = '';

    public function resolve()
    {
        parent::resolve();
        if (!is_array($this->body)) {
            $this->seatNo = $this->body;
        }
    }

    /**
     * @return string
     */
    public function getSeatNo(): string
    {
        return $this->seatNo;
    }
}