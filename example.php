<?php

require_once __DIR__ . '/vendor/autoload.php';

$client = new Jacobemerick\BigstockAPI\Client();
$categories = $client->fetchCategories();
var_dump($categories);
