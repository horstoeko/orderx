<?php

use horstoeko\orderx\OrderXsdValidator;
use horstoeko\orderx\OrderDocumentReader;

require dirname(__FILE__) . "/../vendor/autoload.php";

function showValidationResult($xsdValidator)
{
    if ($xsdValidator->validationFailed()) {
        echo "\033[01;31mValidation failed\e[0m\n";
        foreach ($xsdValidator->validationErrors() as $validationError) {
            echo $validationError . PHP_EOL;
        }
    } else {
        echo "\033[01;32mValidation passed\e[0m\n";
    }
}

/**
 * Valid XML
 */

$document = OrderDocumentReader::readAndGuessFromFile(dirname(__FILE__) . "/order-x.xml");

$xsdValidator = new OrderXsdValidator($document);
$xsdValidator->validate();

showValidationResult($xsdValidator);
