<?php

namespace MyERP\Exception;


use MyERP\Exception;

class APIException extends Exception{

    /**
     * @var int
     */
    public $code;

    /**
     * @var string
     */
    public $reason;

    /**
     * @var string
     */
    public $message;

    /**
     * @param string $message
     * @param int $code
     * @param string $reason
     * @param \Exception $previous
     */
    public function __construct($message, $code, $reason, \Exception $previous = null){
        $this->message = $message;
        $this->code = $code;
        $this->reason = $reason;
        parent::__construct($message, $code, $previous);
    }


}
