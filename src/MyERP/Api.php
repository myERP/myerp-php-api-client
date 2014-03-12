<?php

namespace MyERP;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\HttpException;
use MyERP\Exception\APIException;
use MyERP\Exception;
use MyERP\ApiResponse;

abstract class Api{

    protected $apiEmail;
    protected $apiKey;
    protected $params;
    protected $baseUrl;

    public function __construct($apiEmail, $apiKey, $params){
      $this->apiEmail = $apiEmail;
      $this->apiKey = $apiKey;
      $this->params = $params;
      $url = $params['protocol'] . '://';
      if (($params['protocol'] == 'http' && $params['port'] == 80) || ($params['protocol'] == 'https' && $params['port'] == 443))
        $url .= $params['host'];
      else
        $url .= $params['host'] . ':' . $params['port'];
      $url .= $params['prefix'];

      $this->baseUrl = $url;
    }


    public function setBaseUrl($url){
        $this->baseUrl = $url;
    }

    public function all() {
      return $this->findAll();
    }

    public function findAll() {
      $limit = 100; $page = 0; $res = [];
      do {
	$url = $this->getUrl() . ('?' . http_build_query(['offset' => $page * $limit, 'limit'=> $limit]));
	$response = $this->request($url, array(), 'GET');
	$res = array_merge($res, $response->body);
	$page++;
      } while ($response->hasNextPage());
      return $res;
    }

    /**
    * $id
    *   - null returns all the element of the list
    *   - integer > 0 for a single element
    */
    public function find($id) {
      if (is_int($id) && $id > 0) {
	return $this->request($this->getUrl($id), array(), 'GET')->body;
      }
      else {
        throw new Exception('Usage: the parameter $id should be an integer > 0');
      }
    }

    /**
    * $id integer > 0 for a single deletion
    */
    public function delete($id) {
      if (is_int($id) && $id > 0) {
		return $this->request($this->getUrl($id), array(), 'DELETE')->body;
      }
      else {
	throw new Exception('Usage: the parameter should be an integer for a single deletion');
      }
    }


    /**
    * $payload an object to create or update depending on the id
    */
    public function save(array $payload = array()) {
      if(isset($payload['id']) && is_int($payload['id']) && $payload['id'] > 0) {
        return $this->put($payload['id'], $payload);
      }
      else {
        return $this->post($payload);
      }
    }

    private function put($id = null, array $body = array()) {
      return $this->request($this->getUrl($id), $body, 'PUT')->body;
    }

    private function post(array $body = array()) {
      return $this->request($this->getUrl(), $body, 'POST')->body;
    }

    private function request($url, array $body = array(), $method){
        $json = json_encode($body);
        $client = new Client($this->baseUrl);
        switch ($method) {
          case 'GET':
            $request = $client->get($url, NULL, $json);
            break;
          case 'PUT':
            $request = $client->put($url, NULL, $json);
            break;
          case 'POST':
            $request = $client->post($url, NULL, $json);
            break;
          case "DELETE":
            $request = $client->delete($url, NULL, $json);
            break;
        }
        $request = $request->setAuth($this->apiEmail, $this->apiKey);
        try{
            $response = $request->send();
            $body = $response->getBody();
            $headers = $response->getHeaders();
        }
        catch(HttpException $e){
            $response = json_decode($e->getResponse()->getBody(), true);
            throw new APIException($response['error']['message'], $response['error']['code'], isset($response['error']['reason']) ? $response['error']['reason'] : '', $e);
        }

        return new ApiResponse($headers, json_decode($body, true));
    }

    private function getUrl($id = null) {
      $class_name = explode('\\', get_called_class());
      $url = $this->from_camel_case(end($class_name));
      if (is_int($id) && $id > 0)
        $url .= '/' . $id;
      return $url;
    }

    private function from_camel_case($input) {
      preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
      $ret = $matches[0];
      foreach ($ret as &$match) {
        $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
      }
      return implode('_', $ret);
    }
}
