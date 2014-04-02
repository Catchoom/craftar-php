<?php
// (C) Catchoom Technologies S.L.
// Licensed under the MIT license.
// https://github.com/catchoom/catchoom-php/blob/master/LICENSE
// All warranties and liabilities are disclaimed.

// include Catchoom API libraries
include("lib/CatchoomRecognition.php");
include("lib/CatchoomManagement.php");

// use your own api_key!
$apiKey = "use your own api_key!";

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

// to create trackable items ( AR Items )
// $optionalData["tracking"] = true

echo "creating item...\n";
$response = $catchoomManagement->createItem($collection->uuid, $name, $optionalData);
$item = $response->getBody();

// Upload an image representing your item.
echo "Uploading reference image...\n";
$response = $catchoomManagement->createImage($item->uuid, "./images/reference/catchy.jpg");
$image = $response->getBody();

// Now you are ready to perform the visual recognition against your collection.
//
// Note that before performing a successful recognition, the corresponding reference image
// needs to be fully indexed by the server. Normally it takes less than one second after uploading.
sleep(1);

// Instatntiate a new Catchoom Recognition object
$catchoomRecognition = new CatchoomRecognition(CatchoomRecognition::API_VERSION_1, $token);

// perform the search
echo "performing Imgage Recognition...\n";

// optional arguments
$options = array();

// to retrieve custom data embeded uncomment the option below
// $options["embed_custom"] "true";

// to retrieve tracking info embeded uncomment the option below
// $options = array("embed_tracking" => "true");

$response = $catchoomRecognition->search("./images/query/query_01.jpg", $options);

// pretty print search results
echo "Response:\n\n";
print_r($response->getBody());
