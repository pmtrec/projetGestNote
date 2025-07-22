<?php

class APIException extends Exception {
    private $details;
    
    public function __construct(int $code, string $message, string $details = null) {
        parent::__construct($message, $code);
        $this->details = $details;
    }
    
    public function getDetails(): ?string {
        return $this->details;
    }
}
