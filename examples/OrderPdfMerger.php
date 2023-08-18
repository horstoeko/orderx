<?php

use horstoeko\orderx\OrderDocumentPdfMerger;

require getcwd() . "/../vendor/autoload.php";

$pdfMerger = new OrderDocumentPdfMerger(dirname(__FILE__) . "/order-x.xml", dirname(__FILE__) . "/../src/assets/empty.pdf");
$pdfMerger->generateDocument();
$pdfMerger->saveDocument(dirname(__FILE__) . "/order-x.pdf");
