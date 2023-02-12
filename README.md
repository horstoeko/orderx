# Order-X

[![Latest Stable Version](https://poser.pugx.org/horstoeko/orderx/v/stable.png)](https://packagist.org/packages/horstoeko/orderx) [![Total Downloads](https://poser.pugx.org/horstoeko/orderx/downloads.png)](https://packagist.org/packages/horstoeko/orderx) [![Latest Unstable Version](https://poser.pugx.org/horstoeko/orderx/v/unstable.png)](https://packagist.org/packages/horstoeko/orderx) [![License](https://poser.pugx.org/horstoeko/orderx/license.png)](https://packagist.org/packages/horstoeko/orderx) [![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/horstoeko/orderx)

A simple Order-X Library

With `horstoeko/orderx` you can read and write xml files containing electronic orders data in all Profiles.

Information about...
* [OrderX](https://www.ferd-net.de/standards/order-x/index.html) (German)

This package makes use of [JMS Serializer](http://jmsyst.com/libs/serializer), [Xsd2Php](https://github.com/goetas-webservices/xsd2php), [FPDF](https://github.com/Setasign/FPDF) and  [FPDI](https://github.com/Setasign/FPDI).

## Installation

There is one recommended way to install `horstoeko/orderx` via [Composer](https://getcomposer.org/):

* adding the dependency to your ``composer.json`` file:

```js
  "require": {
      ..
      "horstoeko/orderx":"^1",
      ..
  },
```

## Usage

For detailed eplanation you may have a look in the `examples` of this package and the documentation attached to every release.

### Reading a xml file

```php
use horstoeko\orderx\OrderDocumentReader;

$document = OrderDocumentReader::readAndGuessFromFile(dirname(__FILE__) . "/order-x.xml");

$document->getDocumentInformation(
    $documentNo,
    $documentTypeCode,
    $documentDate,
    $documentCurrency,
    $documentName,
    $documentLanguageId,
    $documentEffectiveSpecifiedPeriod,
    $documentPurposeCode,
    $documentRequestedResponseTypeCode)

echo "The Order No. is {$documentno}" . PHP_EOL;
```

### Reading a pdf file with xml attachment

```php
use horstoeko\orderx\OrderDocumentPdfReader;

$document = OrderDocumentPdfReader::readAndGuessFromFile(dirname(__FILE__) . "/order-x.pdf");

$document->getDocumentInformation(
    $documentNo,
    $documentTypeCode,
    $documentDate,
    $documentCurrency,
    $documentName,
    $documentLanguageId,
    $documentEffectiveSpecifiedPeriod,
    $documentPurposeCode,
    $documentRequestedResponseTypeCode)

echo "The Order No. is {$documentno}" . PHP_EOL;
```

### Writing a xml file

```php
use horstoeko\orderx\OrderProfiles;
use horstoeko\orderx\OrderDocumentBuilder;

$document = OrderDocumentBuilder::createNew(
    OrderProfiles::PROFILE_EXTENDED
);

$document
    ->setIsDocumentCopy(false)
    ->setIsTestDocument(false)
    ->setDocumentBusinessProcessSpecifiedDocumentContextParameter("A1")

    ->setDocumentInformation("PO123456789", "220", new \DateTime(), "EUR", "Doc Name", null, new \DateTime(), "9", "AC")
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
    ->setDocumentBlanketOrderReferencedDocument("BLANKET_ORDER_OD", new \DateTime())
    ->setDocumentPreviousOrderChangeReferencedDocument("PREV_ORDER_C_ID", new \DateTime())
    ->setDocumentPreviousOrderResponseReferencedDocument("PREV_ORDER_R_ID", new \DateTime())
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

    ->setDocumentRequestedDeliverySupplyChainEvent(new \DateTime(), new \DateTime(), new \DateTime())

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
    ->setDocumentReceivableSpecifiedTradeAccountingAccount("BUYER_ACCOUNT_REF")

    ->addNewPosition("1")
    ->setDocumentPositionNote("WEEE Tax of 0,50 euros per item included", null, "TXD")
    ->setDocumentPositionProductDetails("Product Name", "Product Description", "987654321", "654987321", "0160", "1234567890123", "Product Batch ID (lot ID)", "Product Brand Name")
    ->addDocumentPositionProductCharacteristic("Characteristic Description", "5 meters", "Characteristic_Code")
    ->addDocumentPositionProductClassification("Class_code", "Name Class Codification", "TST")
    ->setDocumentPositionProductInstance("Product Instances Batch ID", "Product Instances Supplier Serial ID")
    ->setDocumentPositionSupplyChainPackaging("7B", 5, "MTR", 3, "MTR", 1, "MTR")
    ->setDocumentPositionProductOriginTradeCountry("FR")
    ->setDocumentPositionProductReferencedDocument("ADD_REF_PROD_ID", "6", "ADD_REF_PROD_URIID", null, "ADD_REF_PROD_Desc")
    ->setDocumentPositionBuyerOrderReferencedDocument("1")
    ->setDocumentPositionQuotationReferencedDocument("QUOT_125487", "3")
    ->addDocumentPositionAdditionalReferencedDocument("ADD_REF_DOC_ID", "916", "ADD_REF_DOC_URIID", 5, "ADD_REF_DOC_Desc")
    ->addDocumentPositionAdditionalReferencedDocument("OBJECT_125487", "130", null, null, null, "AWV")
    ->setDocumentPositionGrossPrice(10.50, 1, "C62")
    ->addDocumentPositionGrossPriceAllowanceCharge(1.00, false, "DISCOUNT", "95")
    ->addDocumentPositionGrossPriceAllowanceCharge(0.50, true, "WEEE", "AEW")
    ->setDocumentPositionNetPrice(10, 1, "C62")
    ->setDocumentPositionCatalogueReferencedDocument("CATALOG_REF_ID", 2)
    ->setDocumentPositionBlanketOrderReferencedDocument(2)
    ->setDocumentPositionPartialDelivery(true)
    ->setDocumentPositionDeliverReqQuantity(6, "C62")
    ->setDocumentPositionDeliverPackageQuantity(3, "C62")
    ->setDocumentPositionDeliverPerPackageQuantity(2, "C62")
    ->addDocumentPositionRequestedDeliverySupplyChainEvent(null, new \DateTime(), new \DateTime())
    ->addDocumentPositionTax("S", "VAT", 20.0)
    ->addDocumentPositionAllowanceCharge(6.00, false, 10.0, 60.0, "64", "SPECIAL AGREEMENT")
    ->addDocumentPositionAllowanceCharge(6.00, true, 10.0, 60.0, "FC", "FREIGHT SERVICES")
    ->setDocumentPositionLineSummation(60.0)
    ->setDocumentPositionReceivableTradeAccountingAccount("BUYER_ACCOUNTING_REF")

    ->writeFile(getcwd() . "/order-x.xml");
```

### Writing a pdf file with attached xml file

```php
use horstoeko\orderx\OrderProfiles;
use horstoeko\orderx\OrderDocumentBuilder;
use horstoeko\orderx\OrderDocumentPdfBuilder;

$document = OrderDocumentBuilder::createNew(
    OrderProfiles::PROFILE_EXTENDED
);

$document
    ->setIsDocumentCopy(false)
    ->setIsTestDocument(false)
    ->setDocumentBusinessProcessSpecifiedDocumentContextParameter("A1")

    ->setDocumentInformation("PO123456789", "220", new \DateTime(), "EUR", "Doc Name", null, new \DateTime(), "9", "AC");

    // Do some other stuff (see above)

$pdfDocument = new OrderDocumentPdfBuilder(
    $document,
    dirname(__FILE__) . "/../src/assets/empty.pdf"
);

$pdfDocument
    ->generateDocument()
    ->saveDocument(getcwd() . "/order-x.pdf");

```

## Note

The code in this project is provided under the [MIT](https://opensource.org/licenses/MIT) license.
