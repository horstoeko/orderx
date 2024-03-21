<?php

use horstoeko\orderx\OrderProfiles;
use horstoeko\orderx\OrderDocumentBuilder;
use horstoeko\orderx\OrderDocumentValidator;
use horstoeko\orderx\OrderDocumentPdfBuilder;

require dirname(__FILE__) . "/../vendor/autoload.php";

$dt = \DateTime::createFromFormat("Ymd", "20221225");

$document = OrderDocumentBuilder::CreateNew(
    OrderProfiles::PROFILE_EXTENDED
);

$document
    ->setIsDocumentCopy(false)
    ->setIsTestDocument(false)
    ->setDocumentBusinessProcessSpecifiedDocumentContextParameter("A1")

    ->setDocumentInformation("PO123456789", "220", $dt, "EUR", "Doc Name", null, $dt, "9", "AC")
    ->addDocumentNote("Content of Note", "AAI", "AAI")
    ->setDocumentBuyerReference("BUYER_REF_BU123")

    ->setDocumentSeller("SELLER_NAME", "SUPPLIER_ID_321654", "SELLER_ADD_LEGAL_INFORMATION")
    ->addDocumentSellerGlobalId("123654879", "0088")
    ->addDocumentSellerTaxRegistration("VA", "FR 32 123 456 789")
    ->addDocumentSellerTaxRegistration("FC", "SELLER_TAX_ID")
    ->setDocumentSellerAddress("SELLER_ADDR_1", "SELLER_ADDR_2", "SELLER_ADDR_3", "75001", "SELLER_CITY", "FR")
    ->setDocumentSellerLegalOrganisation("123456789", "0002", "SELLER_TRADING_NAME")
    ->setDocumentSellerContact("SELLER_CONTACT_NAME", "SELLER_CONTACT_DEP", "+33 6 25 64 98 75", null, "contact@seller.com", "SR")
    ->setDocumentSellerElectronicAddress("EM", "sales@seller.com")

    ->setDocumentBuyer("BUYER_NAME", "BY_ID_9587456")
    ->addDocumentBuyerGlobalId("98765432179", "0088")
    ->addDocumentBuyerTaxRegistration("VA", "FR 05 987 654 321")
    ->setDocumentBuyerAddress("BUYER_ADDR_1", "BUYER_ADDR_2", "BUYER_ADDR_3", "69001", "BUYER_CITY", "FR")
    ->setDocumentBuyerLegalOrganisation("987654321", "0002", "BUYER_TRADING_NAME")
    ->setDocumentBuyerContact("BUYER_CONTACT_NAME", "BUYER_CONTACT_DEP", "+33 6 65 98 75 32", null, "contact@buyer.com", "LB")
    ->setDocumentBuyerElectronicAddress("EM", "operation@buyer.com")

    ->setDocumentBuyerRequisitioner("BUYER_REQ_NAME", "BUYER_REQ_ID_25987")
    ->addDocumentBuyerRequisitionerGlobalId("654987321", "0088")
    ->addDocumentBuyerRequisitionerTaxRegistration("VA", "FR 92 654 987 321")
    ->setDocumentBuyerRequisitionerAddress("BUYER_REQ_ADDR_1", "BUYER_REQ_ADDR_2", "BUYER_REQ_ADDR_3", "69001", "BUYER_REQ_CITY", "FR")
    ->setDocumentBuyerRequisitionerLegalOrganisation("654987321", "0022", "BUYER_REQ_TRADING_NAME")
    ->setDocumentBuyerRequisitionerContact("BUYER_REQ_CONTACT_NAME", "BUYER_REQ_CONTACT_DEP", "+33 6 54 98 65 32", null, "requisitioner@buyer.com", "PD")
    ->setDocumentBuyerRequisitionerElectronicAddress("EM", "purchase@buyer.com")

    ->setDocumentDeliveryTerms("FCA", "Free Carrier", "7", "DEL_TERMS_LOC_ID", "DEL_TERMS_LOC_Name")

    ->setDocumentSellerOrderReferencedDocument("SALES_REF_ID_459875")
    ->setDocumentBuyerOrderReferencedDocument("PO123456789")
    ->setDocumentQuotationReferencedDocument("QUOT_125487")
    ->setDocumentContractReferencedDocument("CONTRACT_2020-25987")
    ->setDocumentRequisitionReferencedDocument("REQ_875498")
    ->addDocumentAdditionalReferencedDocument("916", "ADD_REF_DOC_ID", "ADD_REF_DOC_URIID", "ADD_REF_DOC_Desc")
    ->addDocumentAdditionalReferencedDocument("50", "TENDER_ID")
    ->addDocumentAdditionalReferencedDocument("130", "OBJECT_ID", null, null, "AWV")
    ->setDocumentBlanketOrderReferencedDocument("BLANKET_ORDER_OD", $dt)
    ->setDocumentPreviousOrderChangeReferencedDocument("PREV_ORDER_C_ID", $dt)
    ->setDocumentPreviousOrderResponseReferencedDocument("PREV_ORDER_R_ID", $dt)
    ->setDocumentProcuringProject("PROJECT_ID", "Project Reference")

    ->setDocumentShipTo("SHIP_TO_NAME", "SHIP_TO_ID")
    ->addDocumentShipToGlobalId("5897546912", "0088")
    ->addDocumentShipToTaxRegistration("VA", "FR 66 951 632 874")
    ->setDocumentShipToAddress("SHIP_TO_ADDR_1", "SHIP_TO_ADDR_2", "SHIP_TO_ADDR_3", "69003", "SHIP_TO_CITY", "FR")
    ->setDocumentShipToLegalOrganisation("951632874", "0002", "SHIP_TO_TRADING_NAME")
    ->setDocumentShipToContact("SHIP_TO_CONTACT_NAME", "SHIP_TO_CONTACT_DEP", "+33 6 85 96 32 41", null, "shipto@customer.com", "SD")
    ->setDocumentShipToElectronicAddress("EM", "delivery@buyer.com")

    ->setDocumentShipFrom("SHIP_FROM_NAME", "SHIP_FROM_ID")
    ->addDocumentShipFromGlobalId("875496123", "0088")
    ->addDocumentShipFromTaxRegistration("VA", "FR 16 548 963 127")
    ->setDocumentShipFromAddress("SHIP_FROM_ADDR_1", "SHIP_FROM_ADDR_2", "SHIP_FROM_ADDR_3", "75003", "SHIP_FROM_CITY", "FR")
    ->setDocumentShipFromLegalOrganisation("548963127", "0002", "SHIP_FROM_TRADING_NAME")
    ->setDocumentShipFromContact("SHIP_FROM_CONTACT_NAME", "SHIP_FROM_CONTACT_DEP", "+33 6 85 96 32 41", null, "shipfrom@seller.com", "SD")
    ->setDocumentShipFromElectronicAddress("EM", "warehouse@seller.com")

    ->setDocumentRequestedDeliverySupplyChainEvent($dt, $dt, $dt)
    ->setDocumentRequestedDespatchSupplyChainEvent($dt, $dt, $dt)

    ->setDocumentInvoicee("INVOICEE_NAME", "INVOICEE_ID_9587456")
    ->addDocumentInvoiceeGlobalId("98765432179", "0088")
    ->addDocumentInvoiceeTaxRegistration("VA", "FR 05 987 654 321")
    ->addDocumentInvoiceeTaxRegistration("FC", "INVOICEE_TAX_ID")
    ->setDocumentInvoiceeAddress("INVOICEE_ADDR_1", "INVOICEE_ADDR_2", "INVOICEE_ADDR_3", "69001", "INVOICEE_CITY", "FR")
    ->setDocumentInvoiceeLegalOrganisation("987654321", "0002", "INVOICEE_TRADING_NAME")
    ->setDocumentInvoiceeContact("INVOICEE_CONTACT_NAME", "INVOICEE_CONTACT_DEP", "+33 6 65 98 75 32", null, "invoicee@buyer.com", "LB")
    ->setDocumentInvoiceeElectronicAddress("EM", "invoicee@buyer.com")

    ->setDocumentPaymentMean("30", "Credit Transfer")
    ->addDocumentPaymentTerm("PAYMENT_TERMS_DESC")
    ->addDocumentAllowanceCharge(31.00, false, "S", "VAT", 20, null, 10.00, 310.00, null, null, "64", "SPECIAL AGREEMENT")
    ->addDocumentAllowanceCharge(21.00, true, "S", "VAT", 20, null, 10.00, 210.00, null, null, "FC", "FREIGHT SERVICES")
    ->setDocumentSummation(310, 360, 21, 31, 300, 60)
    ->setDocumentReceivableSpecifiedTradeAccountingAccount("4711", "1")

    ->addDocumentTax("S", "VAT", 300.00, 60.00, 20.00, "ExcReason-1", "ExcReasonCode-1", 300.00, 300.00, null)

    ->setDocumentUltimateCustomerOrderReferencedDocument("ULTCUSTORDEREF-1", $dt)

    ->addNewPosition("1")
    ->setDocumentPositionNote("WEEE Tax of 0,50 euros per item included", null, "TXD")
    ->setDocumentPositionProductDetails("Product Name", "Product Description", "987654321", "654987321", "0160", "1234567890123", "Product Batch ID (lot ID)", "Product Brand Name")
    ->addDocumentPositionProductCharacteristic("Characteristic Description", "5 meters", "Characteristic_Code")
    ->addDocumentPositionProductClassification("Class_code", "Name Class Codification", "TST")
    ->setDocumentPositionProductInstance("Product Instances Batch ID", "Product Instances Supplier Serial ID")
    ->setDocumentPositionSupplyChainPackaging("7B", 5, "MTR", 3, "MTR", 1, "MTR")
    ->setDocumentPositionProductOriginTradeCountry("FR")
    ->setDocumentPositionProductReferencedDocument("ADD_REF_PROD_ID", "6", "ADD_REF_PROD_URIID", "ADD_REF_PROD_Desc")
    ->setDocumentPositionBuyerOrderReferencedDocument("1")
    ->setDocumentPositionQuotationReferencedDocument("QUOT_125487", "3")
    ->addDocumentPositionAdditionalReferencedDocument("ADD_REF_DOC_ID", "916", "ADD_REF_DOC_URIID", 5, "ADD_REF_DOC_Desc")
    ->addDocumentPositionAdditionalReferencedDocument("OBJECT_125487", "130", null, null, null, "AWV")
    ->setDocumentPositionGrossPrice(10.50, 1, "C62")
    ->addDocumentPositionGrossPriceAllowanceCharge(1.00, false, null, null, "DISCOUNT", null, null, null, null, null, null, "95")
    ->addDocumentPositionGrossPriceAllowanceCharge(0.50, true, null, null, "WEEE", null, null, null, null, null, null, "AEW")
    ->setDocumentPositionNetPrice(10, 1, "C62")
    ->setDocumentPositionCatalogueReferencedDocument("CATALOG_REF_ID", 2)
    ->setDocumentPositionBlanketOrderReferencedDocument(2)
    ->setDocumentPositionPartialDelivery(true)
    ->setDocumentPositionDeliverReqQuantity(6, "C62")
    ->setDocumentPositionDeliverPackageQuantity(3, "C62")
    ->setDocumentPositionDeliverPerPackageQuantity(2, "C62")
    ->setDocumentPositionRequestedDeliverySupplyChainEvent(null, $dt, $dt)
    ->setDocumentPositionRequestedDespatchSupplyChainEvent(null, $dt, $dt)
    ->addDocumentPositionAllowanceCharge(6.00, false, 10.0, 60.0, "64", "SPECIAL AGREEMENT")
    ->addDocumentPositionAllowanceCharge(6.00, true, 10.0, 60.0, "FC", "FREIGHT SERVICES")
    ->setDocumentPositionLineSummation(60.0)
    ->setDocumentPositionReceivableTradeAccountingAccount("4712")
    ->setDocumentPositionTax("S", "VAT", 19.0, 0.00, "Reason-1", "RC1")
    ->setDocumentPositionUltimateCustomerOrderReferencedDocument("ULTCUSTORDEREF-1", "1", $dt)

    ->addNewPosition("2")
    ->setDocumentPositionNote("WEEE Tax of 0,50 euros per item included", null, "TXD")
    ->setDocumentPositionProductDetails("Product Name", "Product Description", "598632147", "698569856", "0160", "548796523", "Product Batch ID (lot ID)", "Product Brand Name")
    ->addDocumentPositionProductCharacteristic("Characteristic Description", "3 meters", "Characteristic_Code")
    ->addDocumentPositionProductClassification("Class_code", "Name Class Codification", "TST")
    ->setDocumentPositionProductInstance("Product Instances Batch ID", "Product Instances Supplier Serial ID")
    ->setDocumentPositionSupplyChainPackaging("7B", 2, "MTR", 1, "MTR", 3, "MTR")
    ->setDocumentPositionProductOriginTradeCountry("FR")
    ->setDocumentPositionProductReferencedDocument("ADD_REF_PROD_ID", "6", "ADD_REF_PROD_URIID", "ADD_REF_PROD_Desc")
    ->setDocumentPositionBuyerOrderReferencedDocument("3")
    ->setDocumentPositionQuotationReferencedDocument("QUOT_125487", "2")
    ->addDocumentPositionAdditionalReferencedDocument("ADD_REF_DOC_ID", "916", "ADD_REF_DOC_URIID", 5, "ADD_REF_DOC_Desc")
    ->addDocumentPositionAdditionalReferencedDocument("OBJECT_125487", "130", null, null, null, "AWV")
    ->setDocumentPositionGrossPrice(19.50, 2, "C62")
    ->addDocumentPositionGrossPriceAllowanceCharge(0.50, true, null, null, "WEEE TAX", null, null, null, null, null, null, "AEW")
    ->setDocumentPositionNetPrice(20, 2, "C62")
    ->setDocumentPositionCatalogueReferencedDocument("CATALOG_REF_ID", 2)
    ->setDocumentPositionBlanketOrderReferencedDocument(3)
    ->setDocumentPositionPartialDelivery(true)
    ->setDocumentPositionDeliverReqQuantity(10, "C62")
    ->setDocumentPositionDeliverPackageQuantity(5, "C62")
    ->setDocumentPositionDeliverPerPackageQuantity(2, "C62")
    ->setDocumentPositionRequestedDeliverySupplyChainEvent($dt)
    ->setDocumentPositionRequestedDespatchSupplyChainEvent($dt)
    ->addDocumentPositionAllowanceCharge(1.00, false, 1.0, 100.0, "64", "SPECIAL AGREEMENT")
    ->addDocumentPositionAllowanceCharge(1.00, true, 1.0, 100.0, "FC", "FREIGHT SERVICES")
    ->setDocumentPositionLineSummation(100.0)
    ->setDocumentPositionReceivableTradeAccountingAccount("4713")
    ->setDocumentPositionUltimateCustomerOrderReferencedDocument("ULTCUSTORDEREF-1", "2", $dt)

    ->addNewPosition("3")
    ->setDocumentPositionNote("Content of Note", null, "AAI")
    ->setDocumentPositionProductDetails("Product Name", "Product Description", "698325417", "598674321", "0160", "854721548", "Product Batch ID (lot ID)", "Product Brand Name")
    ->addDocumentPositionProductCharacteristic("Characteristic Description", "3 meters", "Characteristic_Code")
    ->addDocumentPositionProductClassification("Class_code", "Name Class Codification", "TST")
    ->setDocumentPositionProductInstance("Product Instances Batch ID", "Product Instances Supplier Serial ID")
    ->setDocumentPositionSupplyChainPackaging("7B", 2, "MTR", 1, "MTR", 3, "MTR")
    ->setDocumentPositionProductOriginTradeCountry("FR")
    ->setDocumentPositionProductReferencedDocument("ADD_REF_PROD_ID", "6", "ADD_REF_PROD_URIID", "ADD_REF_PROD_Desc")
    ->setDocumentPositionBuyerOrderReferencedDocument("4")
    ->setDocumentPositionQuotationReferencedDocument("QUOT_125487", "1")
    ->addDocumentPositionAdditionalReferencedDocument("ADD_REF_DOC_ID", "916", "ADD_REF_DOC_URIID", 5, "ADD_REF_DOC_Desc")
    ->addDocumentPositionAdditionalReferencedDocument("OBJECT_125487", "130", null, null, null, "AWV")
    ->setDocumentPositionGrossPrice(30, 1, "C62")
    ->addDocumentPositionGrossPriceAllowanceCharge(5, false)
    ->setDocumentPositionNetPrice(25, 1, "C62")
    ->setDocumentPositionCatalogueReferencedDocument("CATALOG_REF_ID", 5)
    ->setDocumentPositionBlanketOrderReferencedDocument(4)
    ->setDocumentPositionPartialDelivery(true)
    ->setDocumentPositionDeliverReqQuantity(6, "C62")
    ->setDocumentPositionDeliverPackageQuantity(3, "C62")
    ->setDocumentPositionDeliverPerPackageQuantity(2, "C62")
    ->setDocumentPositionRequestedDeliverySupplyChainEvent(null, $dt, $dt)
    ->setDocumentPositionRequestedDespatchSupplyChainEvent(null, $dt, $dt)
    ->addDocumentPositionAllowanceCharge(15.00, false, 10.0, 150.0, "64", "SPECIAL AGREEMENT")
    ->addDocumentPositionAllowanceCharge(15.00, true, 10.0, 150.0, "FC", "FREIGHT SERVICES")
    ->setDocumentPositionLineSummation(150.0)
    ->setDocumentPositionReceivableTradeAccountingAccount("0816")
    ->setDocumentPositionUltimateCustomerOrderReferencedDocument("ULTCUSTORDEREF-1", "3", $dt)

    ->writeFile(getcwd() . "/order-x.xml");

$pdfDocument = new OrderDocumentPdfBuilder(
    $document,
    dirname(__FILE__) . "/../src/assets/empty.pdf"
);

$pdfDocument
    ->generateDocument()
    ->saveDocument(getcwd() . "/order-x.pdf");

(new OrderDocumentValidator($document))
    ->validateDocument();
