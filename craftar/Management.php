<?php
// (C) Catchoom Technologies S.L.
// Licensed under the MIT license.
// https://github.com/Catchoom/craftar-php/blob/master/LICENSE
// All warranties and liabilities are disclaimed.

namespace craftar;

require_once("Request.php");

class Management extends Request{

    const API_VERSION_0 = "v0";

    private $apiVersion;
    private $apiKey;
    private $host;

    public function __construct($apiVersion, $apiKey, $host = "https://my.craftar.net"){
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

    public function getItemListByCollection($collectionUuid, $limit = null, $offset = null){
        return $this->getObjectList("item", $collectionUuid, $limit, $offset);
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
                "file" => $this->file_create($imageFile)
            )
        );
    }

    public function deleteImage($uuid){
        return $this->deleteObject("image", $uuid);
    }

    public function getTokenList($limit = null, $offset = null){
        return $this->getObjectList("token", null, $limit, $offset);
    }

    public function getTokenListByCollection($collectionUuid, $limit = null, $offset = null){
        return $this->getObjectList("token", $collectionUuid, $limit, $offset);
    }

    public function getToken($uuid){
        return $this->getObject("token", $uuid);
    }

    public function createToken($collectionUuid, $tags = null){
        $data = array(
          "collection" => $this->buildResourceUri("collection", $collectionUuid),
        );

        if( $tags != null ){
            $data['tags'] = array_map(
              function($uuid){
                  return $this->buildResourceUri("tag", $uuid);
              },
              $tags
            );
        }

        return $this->createObject("token", $data);
    }

    public function updateToken($uuid, $tags = null){
        $data = (object)array();

        if( $tags != null ){
            $data['tags'] = array_map(
              function($uuid){
                  return $this->buildResourceUri("tag", $uuid);
              },
              $tags
            );
        }

        return $this->updateObject("token", $uuid, $data);
    }

    public function deleteToken($uuid){
        return $this->deleteObject("token", $uuid);
    }

    public function getMediaList($limit = null, $offset = null){
        return $this->getObjectList("media", null, $limit, $offset);
    }

    public function getMedia($uuid){
        return $this->getObject("media", $uuid);
    }

    public function createMedia($mediaFile){
        return $this->createObjectMultipart(
            "media",
            array(
                "file" => $this->file_create($mediaFile)
            )
        );
    }

    public function createVideoMedia($url, $name = null){
        if ( $name == null ){
            $name = basename($url);
        }

        return $this->createObject(
            "media",
            array(
              "name" => $name,
              "mimetype" => "video",
              "meta" => (object)['video-url' => $url]
            )
        );
    }

    public function deleteMedia($uuid){
        return $this->deleteObject("media", $uuid);
    }

    public function getTagList($limit = null, $offset = null){
        return $this->getObjectList("tag", null, $limit, $offset);
    }

    public function getTagListByCollection($collectionUuid, $limit = null, $offset = null){
        return $this->getObjectList("tag", $collectionUuid, $limit, $offset);
    }

    public function getTag($uuid){
        return $this->getObject("tag", $uuid);
    }

    public function createTag($collectionUuid, $name){
        return $this->createObject(
          "tag",
          array(
            "collection" => $this->buildResourceUri("collection", $collectionUuid),
            "name" => $name
          )
        );
    }

    public function deleteTag($uuid){
        return $this->deleteObject("tag", $uuid);
    }

    public function getAppList($limit = null, $offset = null){
        return $this->getObjectList("app", null, $limit, $offset);
    }

    public function getApp($uuid){
        return $this->getObject("app", $uuid);
    }

    public function createApp($collectionUuid, $name){
        return $this->createObject(
          "app",
          array(
            "collection" => $this->buildResourceUri("collection", $collectionUuid),
            "name" => $name
          )
        );
    }

    public function deleteApp($uuid){
        return $this->deleteObject("app", $uuid);
    }

    public function getVersionList($limit = null, $offset = null){
        return $this->getObjectList("version", null, $limit, $offset);
    }

    public function getVersion($uuid){
        return $this->getObject("version", $uuid);
    }

    public function getBundleList($limit = null, $offset = null){
        return $this->getObjectList("collectionbundle", $limit, $offset);
    }

    public function getBundle($uuid){
        return $this->getObject("collectionbundle", $uuid);
    }

    public function createBundle($collectionUUid, $appUuid, $versionUuid, $tagUuid = null){
        $data = array(
          "collection" => $collectionUUid,
          "version" => $versionUuid,
          "app" => $appUuid
        );
        if($tagUuid != null){
            $data = array_merge($data, array("tag" => $tagUuid));
        }
        foreach( $data as $objectType => $uuid ){
            $data[$objectType] = $this->buildResourceUri($objectType, $uuid);
        }

        return $this->createObject(
          "collectionbundle",
          $data
        );
    }

    public function deleteBundle($uuid){
        return $this->deleteObject("collectionbundle", $uuid);
    }
}
