<?php

namespace MyERP\Tests\Api;

use MyERP\Tests\MyERPTestCase;
use MyERP\Exception;

class CustomersTest extends MyERPTestCase {

    protected $accounts;

    protected function setUp() {
    	parent::setUp();
    	$this->customers = $this->client->customers();
    	$this->customers->setBaseUrl($this->getServer()->getUrl());
    }

    public function testFind() {
    	$this->getServer()->enqueue((string) $this->getMockResponse('find'));
    	$response = $this->customers->find(146800);
    	$this->assertArrayHasKey('id', $response);
    	$this->assertArrayHasKey('full_name', $response);
    	$this->assertEquals($response['full_name'], 'myERP');
    }

    public function testFindNotFound() {
    	$this->getServer()->enqueue((string) $this->getMockResponse('find404'));
    	try {
    	    $response = $this->customers->find(146800);
    	    $this->fail('Expected exception not thrown');
    	} catch(\MyERP\Exception\APIException $e) {
    	    $this->assertEquals($e->getCode(), '003');
    	    $this->assertEquals($e->getMessage(), 'The resource was not found.');
    	}
    }

    public function testFindAllwithNextPage() {
    	$this->getServer()->enqueue((string) $this->getMockResponse('findAll'));
    	$response = $this->customers->findAll();
    	$this->assertEquals(3, count($response));
    }
}
