<?php
  /*
  *
  * @package    cryptofyer
  * @class    PoloniexApi
  * @author     Fransjo Leihitu
  * @version    0.2
  *
  * API Documentation : https://poloniex.com/support/api/
  */
  class PoloniexApi extends CryptoExchange implements CryptoExchangeInterface {

    // base exchange api url
    private $exchangeUrl        = "https://poloniex.com/public";
    private $apiVersion         = "";
    private $exchangeTradingUrl = "https://poloniex.com/tradingApi";

    // base url for currency
    private $currencyUrl  = "";

    // class version
    private $_version_major  = "0";
    private $_version_minor  = "2";

    public function __construct($apiKey = null , $apiSecret = null)
    {
        $this->apiKey     = $apiKey;
        $this->apiSecret  = $apiSecret;

        parent::setVersion($this->_version_major , $this->_version_minor);
        parent::setBaseUrl($this->exchangeUrl . $this->apiVersion . "/");
    }

    public function getMarketPair($market = "" , $currency = "") {
      return strtoupper($market . "_" . $currency);
    }


    // get ticket information
    public function getTicker($args  = null) {
      return $this->getErrorReturn("not implemented yet!");
    }

    // get balance
    public function getBalance($args  = null) {
      return $this->getErrorReturn("not implemented yet!");
    }

    // place buy order
    public function buy($args = null) {
      return $this->getErrorReturn("not implemented yet!");
    }

    // place sell order
    public function sell($args = null) {
      return $this->getErrorReturn("not implemented yet!");
    }

    // get open orders
    public function getOrders($args = null) {
      return $this->getErrorReturn("not implemented yet!");
    }

    // get order
    public function getOrder($args = null) {
      return $this->getErrorReturn("not implemented yet!");
    }

    // Get the exchange currency detail url
    public function getCurrencyUrl($args = null) {
      return $this->getErrorReturn("not implemented yet!");
    }

    // Get market history
    public function getMarketHistory($args = null) {
      return $this->getErrorReturn("not implemented yet!");
    }


  }
?>