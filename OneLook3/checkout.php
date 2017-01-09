<?php

require 'start.php';
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

$product = $_POST['product'];
$price = $_POST['price'];
$shipping = 2.00;

$total = $price + $shipping;

$payer = new Payer();
$payer->setPaymentMethod("paypal");

$item = new Item();
$item->setName($product)
    ->setCurrency('USD')
    ->setQuantity(1)
    ->setPrice($price);

$itemList = new ItemList();
$itemList->setItems([$item]);

$details = new Details();
$details->setShipping($shipping)
    // ->setTax()
    ->setSubtotal($price);

$amount = new Amount();
$amount->setCurrency("USD")
    ->setTotal($total)
    ->setDetails($details);

$transaction = new Transaction();
$transaction->setAmount($amount)
    ->setItemList($itemList)
    ->setDescription("Payment description")
    ->setInvoiceNumber(uniqid());

$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl(SITE_URL . "/pay.php?success=true")
    ->setCancelUrl(SITE_URL . "/pay.php?success=false");

$payment = new Payment();
$payment->setIntent("sale")
    ->setPayer($payer)
    ->setRedirectUrls($redirectUrls)
    ->setTransactions([$transaction]);

// $request = clone $payment;

try {
    $payment->create($paypal);
} catch (Exception $e) {
  die($e);
}

$approvalUrl = $payment->getApprovalLink();

header("Location: {$approvalUrl}");

// return $payment;
