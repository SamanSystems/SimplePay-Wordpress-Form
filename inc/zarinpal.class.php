<?php
/**
 * zarinpal
 *
 * zarinpal getway class
 *
 * @copyright	(c) 2013 Masoud Amini
 * @author		Masoud Amini 
 * @license		http://www.opensource.org/licenses/gpl-3.0.html
 * @version		1.0
 */
Class zarinpal {
	/**
	 * zarinpal Wsdl link
	 *
	 * @var string
	 */
	private $WSDL = "http://www.zarinpal.com/WebserviceGateway/wsdl";

	/**
	 * Soap Client
	 */
	private $client;

	/**
	 * Parpal MerchantID
	 *
	 * @var integer
	 */
	public $MerchantID;

	/**
	 * zarinpal price payment
	 *
	 * @var integer
	 */
	public $Price;

	/**
	 * Return URL in from zarinpal
	 *
	 * @var string
	 */
	public $ReturnPath;

	/**
	 * Receipt Number
	 *
	 * @var integer
	 */
	public $ResNumber;

	/**
	 * Tracking Number
	 *
	 * @var integer
	 */
	public $RefNumber;

	/**
	 * Description for payment operations
	 *
	 * @var string
	 */
	public $Description;

	/**
	 * Payer name
	 *
	 * @var string
	 */
	public $Paymenter;

	/**
	 * Email payer
	 *
	 * @var string
	 */
	public $Email;

	/**
	 * Mobile payer
	 *
	 * @var integer
	 */
	public $Mobile;

	/**
	 * Payment price
	 *
	 * @var integer
	 */
	public $PayPrice;

	/**
	 * Constructors
	 */
	public function __construct() {
		$this->client = new SoapClient($this->WSDL);
	}

	/**
	 * Request for payment transactions
	 */
	public function Request() {
        $callBackUrl = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];


		$client = new SoapClient('http://www.zarinpal.com/WebserviceGateway/wsdl', array('encoding'=>'UTF-8'));
		$res = $client->PaymentRequest($this->MerchantID,  $this->Price, $callBackUrl, urlencode($this->Description) );
		$PayPath =  'https://www.zarinpal.com/users/pay_invoice/'.$res;
		$Status = $res;
		if(strlen($Status) == 36) {
			//header("Location: $PayPath");
			echo "<meta http-equiv='Refresh' content='0;URL=$PayPath'>";
		} else {
			return $Status; 
		}

	}

	/**
	 * Verify Payment
	 */
	public function Verify() {

		if(isset($_GET['au']) && strlen($_GET['au']) == 36 ) {

			$au = $_GET['au'];
			$this->RefNumber = $_GET['refid'];
			$this->ResNumber = $_GET['resnumber'];
            $client = new SoapClient('http://www.zarinpal.com/WebserviceGateway/wsdl', array('encoding'=>'UTF-8'));
	        $res = $client->PaymentVerification($this->MerchantID, $au, $this->Price);
			$Status = $res;
			//$this->PayPrice = $res;
			$eemail = $this->Email;
			return $Status;

		}
	}
}