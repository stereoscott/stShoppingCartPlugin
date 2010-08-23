<?php

/**
 * Base actions for the stShoppingCartPlugin shopping_cart_purchase module.
 * 
 * @package     stShoppingCartPlugin
 * @subpackage  shopping_cart_purchase
 * @author      Scott Meves <scott@stereointeractive.com>
 * @version     SVN: $Id: BaseActions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
abstract class Baseshopping_cart_purchaseActions extends sfActions
{
  
  public function executeOrder($sfRequest)
  {
    	/*==================================================================
    	 Payflow Direct Payment Call
    	 ===================================================================
    	*/
    require_once ("paypalfunctions.php");

    if ( $PaymentOption == "Visa" || $PaymentOption == "MasterCard" || $PaymentOption == "Amex" || $PaymentOption == "Discover" )
    {
    	/*
    	'------------------------------------
    	' The paymentAmount is the total value of 
    	' the shopping cart, that was set 
    	' earlier in a session variable 
    	' by the shopping cart page
    	'------------------------------------
    	*/

    	$finalPaymentAmount =  $_SESSION["Payment_Amount"];

    	$creditCardType 		= "<<Visa/MasterCard/Amex/Discover>>"; //' Set this to one of the acceptable values (Visa/MasterCard/Amex/Discover) match it to what was selected on your Billing page
    	$creditCardNumber 	= "<<CC number>>"; // ' Set this to the string entered as the credit card number on the Billing page
    	$expDate 				= "<<Expiry Date>>"; // ' Set this to the credit card expiry date entered on the Billing page
    	$cvv2 				= "<<cvv2>>"; // ' Set this to the CVV2 string entered on the Billing page 
    	$firstName 			= "<<firstName>>"; // ' Set this to the customer's first name that was entered on the Billing page 
    	$lastName 			= "<<lastName>>"; // ' Set this to the customer's last name that was entered on the Billing page 
    	$street 				= "<<street>>"; // ' Set this to the customer's street address that was entered on the Billing page 
    	$city 				= "<<city>>"; // ' Set this to the customer's city that was entered on the Billing page 
    	$state 				= "<<state>>"; // ' Set this to the customer's state that was entered on the Billing page 
    	$zip 					= "<<zip>>"; // ' Set this to the zip code of the customer's address that was entered on the Billing page 
    	$countryCode 			= "<<PayPal Country Code>>"; // ' Set this to the PayPal code for the Country of the customer's address that was entered on the Billing page 
    	$currencyCode 		= "<<PayPal Currency Code>>"; // ' Set this to the PayPal code for the Currency used by the customer
    	$orderDescription 	= "<<OrderDescription>>"; // ' Set this to the textual description of this order 

    	$paymentType			= "Sale";

    	/*
    	'------------------------------------
    	'
    	' The DirectPayment function is defined in the file PayPalFunctions.php,
    	' that is included at the top of this file.
    	'-------------------------------------------------
    	*/

    	$resArray = DirectPayment ( $paymentType, $finalPaymentAmount, $creditCardType, $creditCardNumber, $expDate, $cvv2, $firstName, $lastName, $street, $city, $state, $zip, $countryCode, $currencyCode, $orderDescription );
    	$ack = $resArray["RESULT"];
    	if( $ack != "0" ) 
    	{
    		// See Table 4.2 and 4.3 in http://www.paypal.com/en_US/pdf/PayflowPro_Guide.pdf for a list of RESULT values (error codes)
    		//Display a user friendly Error on the page using any of the following error information returned by Payflow
    		$ErrorCode = $ack;
    		$ErrorMsg = $resArray["RESPMSG"];

    		echo "Credit Card transaction failed. ";
    		echo "Error Message: " . $ErrorMsg;
    		echo "Error Code: " . $ErrorCode;
    	}
    }
  }


}
