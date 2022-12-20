<?php

use horstoeko\orderx\OrderDocumentPdfReader;

require dirname(__FILE__) . "/../vendor/autoload.php";

$document = OrderDocumentPdfReader::readAndGuessFromFile(dirname(__FILE__) . "/order-x.pdf");

$document->getDocumentInformation($documentNo, $documentTypeCode, $documentDate, $documentCurrency, $documentName, $documentLanguageId, $documentEffectiveSpecifiedPeriod, $documentPurposeCode, $documentRequestedResponseTypeCode);

echo $documentNo . PHP_EOL;
echo $documentTypeCode . PHP_EOL;
echo $documentDate->format('d.m.Y') . PHP_EOL;
echo $documentCurrency . PHP_EOL;
echo $documentName . PHP_EOL;
echo $documentLanguageId . PHP_EOL;
echo $documentPurposeCode . PHP_EOL;
echo $documentRequestedResponseTypeCode . PHP_EOL;
echo ($documentEffectiveSpecifiedPeriod ? $documentEffectiveSpecifiedPeriod->format('d.m.Y') : "null") . PHP_EOL;
