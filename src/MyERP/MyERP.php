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
      $this->items = new Api\items($this->apiEmail, $this->apiKey, $this->params);
      return $this->items;
    }
}
