<?php

use horstoeko\orderx\OrderDocumentPdfReader;
use horstoeko\orderx\OrderDocumentReader;

require dirname(__FILE__) . "/../vendor/autoload.php";

//$document = OrderDocumentPdfReader::readAndGuessFromFile(dirname(__FILE__) . "/order-x.pdf");
$document = OrderDocumentReader::readAndGuessFromFile(dirname(__FILE__) . "/order-x.xml");

$document->getDocumentInformation($documentNo, $documentTypeCode, $documentDate, $documentCurrency, $documentName, $documentLanguageId, $documentEffectiveSpecifiedPeriod, $documentPurposeCode, $documentRequestedResponseTypeCode);
$document->getIsDocumentCopy($documentIsCopy);
$document->getIsTestDocument($documentIsTest);

echo $documentNo . PHP_EOL;
echo $documentTypeCode . PHP_EOL;
echo $documentDate->format('d.m.Y') . PHP_EOL;
echo $documentCurrency . PHP_EOL;
echo $documentName . PHP_EOL;
echo $documentLanguageId . PHP_EOL;
echo $documentPurposeCode . PHP_EOL;
echo $documentRequestedResponseTypeCode . PHP_EOL;
echo ($documentEffectiveSpecifiedPeriod ? $documentEffectiveSpecifiedPeriod->format('d.m.Y') : "null") . PHP_EOL;
echo "Copy: " . ($documentIsCopy === true ? "Yes" : "No") . PHP_EOL;
echo "Test: " . ($documentIsTest === true ? "Yes" : "No") . PHP_EOL;

if ($document->firstDocumentNote()) {
    $document->getDocumentNote($content, $subjectCode, $contentCode);
    echo "{$subjectCode}, {$contentCode}, " . implode(", ", $content) . PHP_EOL;
    echo ($document->nextDocumentNote() === true ? "Next note found" : "There is no another note") . PHP_EOL;
}

$document->getDocumentSummation($lineTotalAmount, $grandTotalAmount, $chargeTotalAmount, $allowanceTotalAmount, $taxBasisTotalAmount, $taxTotalAmount);
echo "Line Total Amount ........ {$lineTotalAmount}" . PHP_EOL;
echo "Grand Total Amount ....... {$grandTotalAmount}" . PHP_EOL;
echo "Charge Total Amount ...... {$chargeTotalAmount}" . PHP_EOL;
echo "Allowance Total Amount ... {$allowanceTotalAmount}" . PHP_EOL;
echo "Tax Basis Total Amount ... {$taxBasisTotalAmount}" . PHP_EOL;
echo "Tax Total Amount ......... {$taxTotalAmount}" . PHP_EOL;

$document->getDocumentBuyerReference($buyerReference);
echo "The Buyer's reference is {$buyerReference}" . PHP_EOL;

$document->getDocumentSeller($name, $id, $description);
echo "Seller name .............. {$name}" . PHP_EOL;
echo "Seller description ....... {$description}" . PHP_EOL;

$document->getDocumentSellerGlobalId($globalids);
//var_dump($globalids);

$document->getDocumentSellerTaxRegistration($taxreg);
//var_dump($taxreg);

$document->getDocumentSellerAddress($lineone, $linetwo, $linethree, $postcode, $city, $country, $subdivision);
echo "Seller Addr. Line 1 ............ {$lineone}" . PHP_EOL;
echo "Seller Addr. Line 2 ............ {$linetwo}" . PHP_EOL;
echo "Seller Addr. Line 3 ............ {$linethree}" . PHP_EOL;
echo "Seller Postcode ................ {$postcode}" . PHP_EOL;
echo "Seller City .................... {$city}" . PHP_EOL;
echo "Seller Counters ................ {$country}" . PHP_EOL;
echo "Seller Subdivision ............. {$subdivision}" . PHP_EOL;

$document->getDocumentSellerLegalOrganisation($legalorgid, $legalorgtype, $legalorgname);
echo "Seller Legal Org. ID ........... {$legalorgid}" . PHP_EOL;
echo "Seller Legal Org. Type ......... {$legalorgtype}" . PHP_EOL;
echo "Seller Legal Org. Name ......... {$legalorgname}" . PHP_EOL;

if ($document->firstDocumentSellerContact()) {
    $document->getDocumentSellerContact($contactpersonname, $contactdepartmentname, $contactphoneno, $contactfaxno, $contactemailadd, $contacttypecode);
    echo "Seller Contact Person Name ..... {$contactpersonname}" . PHP_EOL;
    echo "Seller Contact Dep. Name ....... {$contactdepartmentname}" . PHP_EOL;
    echo "Seller Contact Phone No. ....... {$contactphoneno}" . PHP_EOL;
    echo "Seller Contact Fax No. ......... {$contactfaxno}" . PHP_EOL;
    echo "Seller Contact E-Mail .......... {$contactemailadd}" . PHP_EOL;
}

$document->getDocumentSellerElectronicAddress($uriType, $uriId);
echo "Seller Electr. Addr. Type ...... {$uriType}" . PHP_EOL;
echo "Seller Electr. Addr. ........... {$uriId}" . PHP_EOL;
