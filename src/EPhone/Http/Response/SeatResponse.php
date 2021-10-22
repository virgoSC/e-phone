<?php

namespace EPhone\Http\Response;

class SeatResponse extends Response
{
    private $seatNo = '';

    public function resolve()
    {
        parent::resolve();
        $this->seatNo = $this->body;
    }

    /**
     * @return string
     */
    public function getSeatNo(): string
    {
        return $this->seatNo;
    }
}