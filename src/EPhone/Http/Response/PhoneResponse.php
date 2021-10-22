<?php

namespace EPhone\Http\Response;

class PhoneResponse extends Response
{
    private $taskId = '';

    public function resolve()
    {
        parent::resolve();

        if (is_array($this->body)) {
            $this->taskId = $this->body['taskId'];
        } else {
            $this->taskId = $this->body;
        }
    }

    /**
     * @return string
     */
    public function getTaskId(): string
    {
        return $this->taskId;
    }
}