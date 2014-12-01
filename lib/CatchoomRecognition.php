<?php
// (C) Catchoom Technologies S.L.
// Licensed under the MIT license.
// https://github.com/catchoom/catchoom-php/blob/master/LICENSE
// All warranties and liabilities are disclaimed.

require_once("CatchoomAPI.php");

class CatchoomRecognition extends CatchoomAPI{

    const API_VERSION_0 = "v0";
    const API_VERSION_1 = "v1";

    private $token;
    private $apiVersion;
    private $host;

    public function __construct($apiVersion, $token, $host = 'https://search.craftar.net'){
        $this->token = $token;
        $this->apiVersion = $apiVersion;
        $this->host = $host;
    }

    public function search($queryImage, $options = array()){
        $url = "{$this->host}/{$this->apiVersion}/search";
        $data= array('token' => $this->token, 'image' => "@$queryImage");
        return $this->multipartPost($url, array_merge($data, $options));
    }


}



