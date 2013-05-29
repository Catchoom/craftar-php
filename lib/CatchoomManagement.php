<?php

require_once("CatchoomAPI.php");

class CatchoomManagement extends CatchoomAPI{

    const API_VERSION_0 = "v0";

    private $apiVersion;
    private $apiKey;
    private $host;

    public function __construct($apiVersion, $apiKey, $host = "https://crs.catchoom.com"){
        $this->apiVersion = $apiVersion;
        $this->apiKey = $apiKey;
        $this->host = $host;
    }

    private function buildResourceUri($objectType, $uuid = null){
        $url = "/api/{$this->apiVersion}/$objectType/";

        if ($uuid != null)
            $url .= "$uuid/";

        return $url;
    }

    private function buildUrl($objectType, $uuid = null){
        $url = $this->host;
        $url .= $this->buildResourceUri($objectType, $uuid);
        $url .= "?api_key={$this->apiKey}";
        return $url;
    }

    private function getObjectList($objectType, $filter, $limit = null, $offset = null){
        $url = $this->buildUrl($objectType);
        if ($limit != null)
            $url .= "&limit=$limit";
        if ($offset != null)
            $url .= "&offset=$offset";
        if ($filter != null){
            if ($objectType == "item" || $objectType == "token"){
                $url .= "&collection__uuid=$filter";
            }elseif ($objectType == "image"){
                $url .= "&item__uuid=$filter";
            }
        }
        return $this->get($url);
    }

    private function getObject($objectType, $uuid){
        $url = $this->buildUrl($objectType, $uuid);
        return $this->get($url);
    }

    private function deleteObject($objectType, $uuid){
        $url = $this->buildUrl($objectType, $uuid);
        return $this->del($url);
    }

    private function createObject($objectType, $data){
        $url = $this->buildUrl($objectType);
        return $this->post($url, $data);
    }

    private function createObjectMultipart($objectType, $data){
        $url = $this->buildUrl($objectType);
        return $this->multipartPost($url, $data);
    }

    private function updateObject($objectType, $uuid, $data){
        $url = $this->buildUrl($objectType, $uuid);
        return $this->put($url, $data);
    }

    public function getCollectionList($limit = null, $offset = null){
        return $this->getObjectList("collection", null, $limit, $offset);
    }

    public function getCollection($uuid){
        return $this->getObject("collection", $uuid);
    }

    public function createCollection($name){
        return $this->createObject("collection", array("name" => $name));
    }

    public function deleteCollection($uuid){
        return $this->deleteObject("collection", $uuid);
    }

    public function updateCollection($uuid, $name){
        return $this->updateObject("collection", $uuid, array("name" => $name));
    }

    public function getItemList($limit = null, $offset = null){
        return $this->getObjectList("item", null, $limit, $offset);
    }

    public function getItemListByCollection($collectionUUid, $limit = null, $offset = null){
        return $this->getObjectList("item", $collectionUUid, $limit, $offset);
    }

    public function getItem($uuid){
        return $this->getObject("item", $uuid);
    }

    public function createItem($collectionUuid, $name, $optionalData){
        $data = array(
            "collection" => $this->buildResourceUri("collection", $collectionUuid),
            "name" => $name
        );
        return $this->createObject("item", array_merge($data, $optionalData));
    }

    public function updateItem($itemUuid, $name, $optionalData = array()){
        $data = array(
            "name" => $name
        );
        return $this->updateObject("item", $itemUuid, array_merge($data, $optionalData));
    }

    public function deleteItem($uuid){
        return $this->deleteObject("item", $uuid);
    }

    public function getImageList($limit = null, $offset = null){
        return $this->getObjectList("image", null, $limit, $offset);
    }

    public function getImageListByItem($itemUUid, $limit = null, $offset = null){
        return $this->getObjectList("image", $itemUUid, $limit, $offset);
    }

    public function getImage($uuid){
        return $this->getObject("image", $uuid);
    }

    public function createImage($itemUuid, $imageFile){
        return $this->createObjectMultipart(
            "image",
            array(
                "item" => $this->buildResourceUri("item", $itemUuid),
                "file" => "@$imageFile"
            )
        );
    }

    public function deleteImage($uuid){
        return $this->deleteObject("image", $uuid);
    }

    public function getTokenList($limit = null, $offset = null){
        return $this->getObjectList("token", null, $limit, $offset);
    }

    public function getTokenListByCollection($collectionUUid, $limit = null, $offset = null){
        return $this->getObjectList("token", $collectionUUid, $limit, $offset);
    }

    public function getToken($uuid){
        return $this->getObject("token", $uuid);
    }

    public function createToken($collectionUuid){
        return $this->createObject(
            "token",
            array(
                "collection" => $this->buildResourceUri("collection", $collectionUuid),
            )
        );
    }

    public function deleteToken($uuid){
        return $this->deleteObject("token", $uuid);
    }
}
