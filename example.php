<?php

// include Catchoom API libraries
include("lib/CatchoomRecognition.php");
include("lib/CatchoomManagement.php");

// use your own api_key!
$apiKey = "602aeb90fddb74c29c46e0a9c8d8c0e15d71e09b";

//Instanciate new Catchoom management object with our api key
$catchoomManagement = new CatchoomManagement(CatchoomManagement::API_VERSION_0, $apiKey);

// Create a new collection
echo "Creating collection...\n";
$response = $catchoomManagement->createCollection("My cool collection");
$collection = $response->getBody();

// A token is created automagically when creating a new collection
// We are going to retrive this token for use in later in the recognition
$response = $catchoomManagement->getTokenListByCollection($collection->uuid);
$token = $response->getBody()->objects[0]->token;

// Create an empty item in your collection:
$name = "My cool item"; // use your own item name
$url = "http://catchoom.com"; // and your own url
$custom = "This is my custom data"; // and your own custom data
$optionalData = array("url" => $url, "custom" => $custom);

echo "creating item...\n";
$response = $catchoomManagement->createItem($collection->uuid, $name, $optionalData);
$item = $response->getBody();

// Upload an image representing your item.
echo "Uploading reference image...\n";
$response = $catchoomManagement->createImage($item->uuid, "./images/reference/catchy.jpg");
$image = $response->getBody();

// Now you are ready to perform the visual recognition against your collection.
//
// Note thet Before performing a successful recognition, the corresponding reference image
// needs to be fully indexed by the server. Normally it takes less than one second after uploading.
sleep(1);

// Instatntiate a new Catchoom Recognition obejct
$catchoomRecognition = new CatchoomRecognition(CatchoomRecognition::API_VERSION_0, $token);

//perform the search
echo "performing Imgage Recognition...\n";
$response = $catchoomRecognition->search("./images/query/query_01.jpg");

// pretty print search results
echo "Response:\n\n";
print_r($response->getBody());
