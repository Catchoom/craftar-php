<?php
// (C) Catchoom Technologies S.L.
// Licensed under the MIT license.
// https://github.com/catchoom/catchoom-php/blob/master/LICENSE
// All warranties and liabilities are disclaimed.

class CatchoomAPIResponse{

    private $rawResponse;
    private $status;
    private $header;
    private $body;

    public function __construct($rawResponse, $status, $header, $body){
        $this->rawResponse = $rawResponse;
        $this->status = $status;
        $this->header = $header;
        $this->body = json_decode($body);
    }

    public function getStatus(){
        return $this->status;
    }

    public function getHeader(){
        return $this->header;
    }

    public function getBody(){
        return $this->body;
    }

    public function getRawResponse(){
        return $this->rawResponse;
    }
}
