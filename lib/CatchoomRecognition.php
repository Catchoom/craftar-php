<?php

require_once("CatchoomAPI.php");

class CatchoomRecognition extends CatchoomAPI{

    const API_VERSION_0 = "v0";

    private $token;
    private $apiVersion;
    private $host;

    public function __construct($apiVersion, $token, $host = 'https://r.catchoom.com'){
        $this->token = $token;
        $this->apiVersion = $apiVersion;
        $this->host = $host;
    }

    public function search($queryImage){
        $url = "{$this->host}/{$this->apiVersion}/search";
        $data= array('token' => $this->token, 'image' => "@$queryImage");

        return $this->multipartPost($url, $data);

    }


}



