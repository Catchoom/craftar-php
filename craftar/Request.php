<?php
// (C) Catchoom Technologies S.L.
// Licensed under the MIT license.
// https://github.com/Catchoom/craftar-php/blob/master/LICENSE
// All warranties and liabilities are disclaimed.

namespace craftar;

require("Response.php");

class Request{

    private function parseResponse($response, $request){

        $status = curl_getinfo($request, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($request, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        return new Response($response,$status,$header,$body);
    }

    private function setCommonOptions($request){
        curl_setopt($request, CURLOPT_PORT , 443);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($request, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_HEADER, true);
        curl_setopt($request, CURLOPT_USERAGENT, 'CraftAR PHP 1.0 ( PHP '.phpversion().')');
    }

    protected function multipartPost($url, $data){

        $request = curl_init($url);
        curl_setopt($request, CURLOPT_POST, 1);
        curl_setopt($request, CURLOPT_HTTPHEADER,  array('Content-type: multipart/form-data'));
        curl_setopt($request, CURLOPT_POSTFIELDS, $data);

        $this->setCommonOptions($request);

        $response = curl_exec($request);
        $result = $this->parseResponse($response, $request);
        curl_close($request);

        return $result;

    }

    protected function get($url){
        $request = curl_init($url);
        $this->setCommonOptions($request);

        $response = curl_exec($request);
        $result = $this->parseResponse($response, $request);
        curl_close($request);

        return $result;
    }

    protected function del($url){
        $request = curl_init($url);
        $this->setCommonOptions($request);

        curl_setopt($request, CURLOPT_CUSTOMREQUEST, "DELETE");

        $response = curl_exec($request);
        $result = $this->parseResponse($response, $request);
        curl_close($request);

        return $result;
    }

    protected function post($url, $data){
        $request = curl_init($url);
        curl_setopt($request, CURLOPT_POST, 1);
        curl_setopt($request, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($data, JSON_FORCE_OBJECT));

        $this->setCommonOptions($request);

        $response = curl_exec($request);
        $result = $this->parseResponse($response, $request);
        curl_close($request);

        return $result;
    }

    protected function put($url, $data){
        $request = curl_init($url);

        curl_setopt($request, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($request, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($data, JSON_FORCE_OBJECT));

        $this->setCommonOptions($request);

        $response = curl_exec($request);
        $result = $this->parseResponse($response, $request);
        curl_close($request);

        return $result;
    }

    protected function file_create($filename){
        if (version_compare(PHP_VERSION, '5.5', '>=')){
            return curl_file_create($filename);
        } else {
            return "@$filename";
        }
    }
}



