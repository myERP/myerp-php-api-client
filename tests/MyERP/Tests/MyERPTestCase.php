<?php

namespace MyERP\Tests;

use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Tests\GuzzleTestCase;
use MyERP\MyERP;
use MyERP\Exception\APIException;

class MyERPTestCase extends GuzzleTestCase{

    protected $client;

    protected function setUp(){
        parent::setUp();
        $this->client = new MyERP('api_email', 'api_key');
    }

    protected function getMockResponsePath(){
        $directory = explode('\\', get_called_class());
        $directory = end($directory);
        $directory = str_replace('Test', '', $directory);
        return __DIR__.'/../../mock/'.$directory.'/';
    }

    public function getMockResponse($path){
        return parent::getMockResponse($this->getMockResponsePath().$path.'.txt');
    }
}
