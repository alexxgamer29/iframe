<?php
// Set the path to your JSON file
$jsonFilePath = '../data/data.json';
// Read JSON file
$jsonData = file_get_contents($jsonFilePath);
// Decode JSON data to array
$dataArray = json_decode($jsonData, true);
// Check if decoding was successful
if ($dataArray === null) {
    die("Error decoding JSON data");
} else {
    echo $jsonData;
}