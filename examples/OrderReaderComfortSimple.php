<?php

use horstoeko\orderx\OrderDocumentPdfReader;

require dirname(__FILE__) . "/../vendor/autoload.php";

$document = OrderDocumentPdfReader::readAndGuessFromFile(dirname(__FILE__) . "/order-x.pdf");

var_dump($document);
