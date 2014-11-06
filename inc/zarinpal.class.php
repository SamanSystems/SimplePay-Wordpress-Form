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
	private $wsdl = 'https://www.zarinpal.com/pg/services/WebGate/wsdl';

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
		$this->client = new SoapClient($this->wsdl, array('encoding' => 'UTF-8'));
	}

	/**
	 * Request for payment transactions
	 */
	public function Request() {
		$result = $this->client->PaymentRequest(array(
			'MerchantID' => $this->MerchantID,
			'Amount' => $this->Price,
			'Description' => $this->Description,
			'Email' => $this->Email,
			'Mobile' => $this->Mobile,
			'CallbackURL' => $this->ReturnPath
		));
		if ($result->Status == 100) {
			echo '<meta http-equiv="Refresh" content="0;URL=https://www.zarinpal.com/pg/StartPay/' . $result->Authority . '">';
		} else {
			return $result->Status; 
		}
	}

	/**
	 * Verify Payment
	 */
	public function Verify() {
		if ($_GET['Status'] == 'OK') {
			$res = $this->client->PaymentVerification(array(
				'MerchantID'	=> $this->MerchantID,
				'Authority' 	=> $_GET['Authority'],
				'Amount'	=> $this->Price
			));
			if ($res->Status == 100) {
				$this->RefNumber = $_GET['Authority'];
				$this->ResNumber = $result->RefID;
			}
			return $res->Status;
		}
	}
}
