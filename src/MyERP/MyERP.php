<?php

namespace MyERP;


class MyERP{

    public static $DEFAULT_PARAMS  = [
      'protocol' => 'https',
      'host' => 'app.myerp.com',
      'port' => 443,
      'prefix' => '/api/v1/'
    ];

    protected $apiEmail;
    protected $apiKey;
    protected $params;

    protected $accounts;
    protected $projects;
    protected $items;
    protected $itemFamilies;
    protected $salesOrders;
    protected $accountingTransactions

    /**
     * @param string $apiEmail MyERP API email
     * @param string $apiKey MyERP API key
     * @param array $params
     */
    public function __construct($apiEmail, $apiKey, array $params = array()){
      $this->apiEmail = $apiEmail;
      $this->apiKey = $apiKey;
      $this->params = array_merge(self::$DEFAULT_PARAMS, $params);
    }

    public function accounts(){
      if(isset($this->accounts)){
          return $this->accounts;
      }
      $this->accounts = new Api\Accounts($this->apiEmail, $this->apiKey, $this->params);
      return $this->accounts;
    }

    public function projects(){
      if(isset($this->projects)){
          return $this->projects;
      }
      $this->projects = new Api\Projects($this->apiEmail, $this->apiKey, $this->params);
      return $this->projects;
    }

    public function items(){
      if(isset($this->items)){
          return $this->items;
      }
      $this->items = new Api\Items($this->apiEmail, $this->apiKey, $this->params);
      return $this->items;
    }

    public function itemFamilies(){
      if(isset($this->itemFamilies)){
          return $this->itemFamilies;
      }
      $this->itemFamilies = new Api\ItemFamilies($this->apiEmail, $this->apiKey, $this->params);
      return $this->itemFamilies;
    }

    public function salesOrders(){
      if(isset($this->salesOrders)){
          return $this->salesOrders;
      }
      $this->salesOrders = new Api\SalesOrders($this->apiEmail, $this->apiKey, $this->params);
      return $this->salesOrders;
    }

    public function accountingTransactions(){
      if(isset($this->accountingTransactions)){
          return $this->accountingTransactions;
      }
      $this->accountingTransactions = new Api\AccountingTransactions($this->apiEmail, $this->apiKey, $this->params);
      return $this->accountingTransactions;
    }


}
