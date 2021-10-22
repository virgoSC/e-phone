<?php

namespace EPhone\Http\Response;

class NotifyResponse extends Response
{
    private $seatNo = '';

    private $bindId = '';

    private $callerNum = '';

    private $fwdDstNum = '';

    private $callInTime = '';

    private $callEndTime = '';

    private $recordUrl = '';

    public function building($data)
    {
        $this->code = '200';
        $this->message = 'success';

        $this->body = json_decode($data, true);

        $this->bindId = $this->body['bindId'] ?? '';
        $this->callerNum = $this->body['callerNum'] ?? '';
        $this->fwdDstNum = $this->body['fwdDstNum'] ?? '';
        $this->callInTime = $this->body['callInTime'] ?? '';
        $this->callEndTime = $this->body['callEndTime'] ?? '';
        $this->recordUrl = $this->body['recordUrl'] ?? '';
        return $this;
    }

    /**
     * @return string
     */
    public function getBindId(): string
    {
        return $this->bindId;
    }

    /**
     * @return string
     */
    public function getCallerNum(): string
    {
        return $this->callerNum;
    }

    /**
     * @return string
     */
    public function getFwdDstNum(): string
    {
        return $this->fwdDstNum;
    }

    /**
     * @return string
     */
    public function getCallInTime(): string
    {
        return $this->callInTime;
    }

    /**
     * @return string
     */
    public function getCallEndTime(): string
    {
        return $this->callEndTime;
    }

    /**
     * @return string
     */
    public function getRecordUrl(): string
    {
        return $this->recordUrl;
    }

    /**
     * @return string
     */
    public function getSeatNo(): string
    {
        return $this->seatNo;
    }
}