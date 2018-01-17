<?php
  /*
  *
  * @package    cryptofyer
  * @class    LiveCoinApi
  * @author     Fransjo Leihitu
  * @version    0.2
  *
  * API Documentation :
  */
  class LiveCoinApi extends CryptoExchange implements CryptoExchangeInterface {

    // base exchange api url
    private $exchangeUrl  = "https://api.livecoin.net/";
    private $apiVersion   = "1.0";

    // base url for currency
    private $currencyUrl  = "https://www.livecoin.net/en/trade/index?currencyPair=";

    // class version
    private $_version_major  = "0";
    private $_version_minor  = "2";

    public function __construct($apiKey = null , $apiSecret = null)
    {
        $this->apiKey     = $apiKey;
        $this->apiSecret  = $apiSecret;

        parent::setVersion($this->_version_major , $this->_version_minor);
        parent::setBaseUrl($this->exchangeUrl);
    }

    private function send($method = null , $args = array() , $secure = true) {
      if(empty($method)) return array("status" => false , "error" => "method was not defined!");

      if(isSet($args["market"])) unset($args["market"]);
      if(isSet($args["currencyPair"])) {
        $args["currencyPair"] = str_replace("/" , "%2F" , $args["currencyPair"]);
      }

      if($secure) $args["apikey"] = $this->apiKey;
      $args["nonce"] = time();

      $urlParams  = array();
      foreach($args as $key => $val) {
        $urlParams[]  = $key . "=" . $val;
      }

      $uri  = $this->getBaseUrl() . $method;

      $argsString = join("&" , $urlParams);
      if(!empty($urlParams)) {
          $uri  = $uri . "?" . $argsString;
      }

      $sign = $secure == true ? hash_hmac('sha512',$uri,$this->apiSecret) : null;

      $uri = trim(preg_replace('/\s+/', '', $uri));

      $ch = curl_init($uri);
      if($secure) curl_setopt($ch, CURLOPT_HTTPHEADER, array('apisign:'.$sign));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $execResult = curl_exec($ch);

      // check if there's a curl error
      if(curl_error($ch)) return $this->getErrorReturn(curl_error($ch));

      // try to convert json repsonse to assoc array
      if($obj = json_decode($execResult , true)) {
        if($obj !== null) {
          return $this->getReturn(true,"",$obj);
        } else {
          return $this->getErrorReturn("error");
        }

      } else {
          return $this->getErrorReturn($execResult);
      }
      return false;

    }

    public function getMarketPair($market = "" , $currency = "") {
      return strtoupper($currency . "/" . $market);
    }


    // Returns public data for currencies:
    public function getRestrictions($args = null) {

      $method     = "exchange/restrictions";
      $resultOBJ  = $this->send( $method, $args, false);

      if($resultOBJ["success"]) {
        $result = $resultOBJ["result"];
        return $result;
      } else {
        return $resultOBJ;
      }
    }

    // Returns public data for currencies:
    public function getCoinInfo($args = null) {

      $method     = "info/coinInfo";
      $resultOBJ  = $this->send( $method, $args, false);

      if($resultOBJ["success"]) {
        $result = $resultOBJ["result"];
        return $result;
      } else {
        return $resultOBJ;
      }
    }

    // get ticket information
    public function getMaxbidMinask($args = null) {
      if(isSet($args["_market"]) && isSet($args["_currency"])) {
        $args["market"] = $this->getMarketPair($args["_market"],$args["_currency"]);
        unset($args["_market"]);
        unset($args["_currency"]);
      }
      if(!isSet($args["market"])) return $this->getErrorReturn("required parameter: market");

      $method = "exchange/maxbid_minask";
      $args["currencyPair"] = $args["market"];

      $resultOBJ  = $this->send( $method, $args, false);

      if($resultOBJ["success"]) {
        $result = $resultOBJ["result"];
        return $result;
      } else {
        return $resultOBJ;
      }
    }

    // get ticket information
    public function getTicker($args = null) {
      if(isSet($args["_market"]) && isSet($args["_currency"])) {
        $args["market"] = $this->getMarketPair($args["_market"],$args["_currency"]);
        unset($args["_market"]);
        unset($args["_currency"]);
      }
      if(!isSet($args["market"])) return $this->getErrorReturn("required parameter: market");

      $method = "exchange/ticker";
      $args["currencyPair"] = $args["market"];

      $resultOBJ  = $this->send( $method, $args, false);

      if($resultOBJ["success"]) {
        $result = $resultOBJ["result"];
        return $result;
      } else {
        return $resultOBJ;
      }
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
      if(isSet($args["_market"]) && isSet($args["_currency"])) {
        $args["market"] = $this->getMarketPair($args["_market"],$args["_currency"]);
      }
      if(!isSet($args["market"])) return $this->getErrorReturn("required parameter: market");

       $args["market"]  = str_replace("/" , "%2F" , $args["market"]);
      return $this->currencyUrl . $args["market"];
    }


    // Returns orderbook for every currency pair.
    public function getAllOrderbook($args = null) {

      $method = "exchange/all/order_book";

      if(!isSet($args["groupByPrice"])) $args["groupByPrice"]  = true;
      if(!isSet($args["depth"])) $args["depth"]  = -10;

      $resultOBJ  = $this->send( $method, $args, false);

      if($resultOBJ["success"]) {
        $result = $resultOBJ["result"];
        return $result;
      } else {
        return $resultOBJ;
      }
    }

    // Returns orderbook
    public function getOrderbook($args = null) {
      if(isSet($args["_market"]) && isSet($args["_currency"])) {
        $args["market"] = $this->getMarketPair($args["_market"],$args["_currency"]);
        unset($args["_market"]);
        unset($args["_currency"]);
      }
      if(!isSet($args["market"])) return $this->getErrorReturn("required parameter: market");

      if(!isSet($args["groupByPrice"])) $args["groupByPrice"]  = true;
      if(!isSet($args["depth"])) $args["depth"]  = -10;

      $method = "exchange/order_book";
      $args["currencyPair"] = $args["market"];

      $resultOBJ  = $this->send( $method, $args, false);

      if($resultOBJ["success"]) {
        $result = $resultOBJ["result"];
        return $result;
      } else {
        return $resultOBJ;
      }
    }

    // Get market history
    public function getMarketHistory($args = null) {
      if(isSet($args["_market"]) && isSet($args["_currency"])) {
        $args["market"] = $this->getMarketPair($args["_market"],$args["_currency"]);
        unset($args["_market"]);
        unset($args["_currency"]);
      }
      if(!isSet($args["market"])) return $this->getErrorReturn("required parameter: market");

      $method = "exchange/last_trades";
      $args["currencyPair"] = $args["market"];

      if(!isSet($args["minutesOrHour"])) $args["minutesOrHour"] = false;

      $resultOBJ  = $this->send( $method, $args, false);

      if($resultOBJ["success"]) {
        $result = $resultOBJ["result"];
        return $result;
      } else {
        return $resultOBJ;
      }
    }


  }
?>