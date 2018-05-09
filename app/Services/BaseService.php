<?php

namespace App\Services;

abstract class BaseService {

    protected $errorCode;
    protected $errorMsg;
    protected $logMsg;

    public function sendErrorMessage($result, $errorCode, $errorMsg, $logMsg)
    {
        $this->errorCode = $errorCode;
        $this->errorMsg = $errorMsg;
        $this->logMsg = $logMsg;

        return $result;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    public function getLogMsg()
    {
        return $this->logMsg;
    }
}