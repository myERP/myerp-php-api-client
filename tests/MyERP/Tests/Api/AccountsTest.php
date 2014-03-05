<?php

namespace MyERP\Tests\Api;

use MyERP\Tests\MyERPTestCase;
use MyERP\Exception;

class AccountsTest extends MyERPTestCase {

    protected $accounts;

    protected function setUp() {
        parent::setUp();
        $this->accounts = $this->client->accounts();
        $this->accounts->setBaseUrl($this->getServer()->getUrl());
    }

    public function testFind() {
        $this->getServer()->enqueue((string) $this->getMockResponse('find'));
        $response = $this->accounts->find(146800);
        $body = $response->body;
        $this->assertArrayHasKey('id', $body);
        $this->assertArrayHasKey('full_name', $body);
        $this->assertEquals($body['full_name'], 'myERP');
        $this->assertFalse($response->hasNextPage());
    }

    public function testFindNotFound() {
        $this->getServer()->enqueue((string) $this->getMockResponse('find404'));
        try {
            $response = $this->accounts->find(146800);
            $this->fail('Expected exception not thrown');
        } catch(\MyERP\Exception\APIException $e) {
            $this->assertEquals($e->getCode(), '003');
            $this->assertEquals($e->getMessage(), 'The resource was not found.');
        }
    }

    public function testFindAllwithNextPage() {
        $this->getServer()->enqueue((string) $this->getMockResponse('findAllwithNextPage'));
        $response = $this->accounts->findAll();
        $this->assertEquals(3, count($response->body));
        $this->assertTrue($response->hasNextPage());
    }
      public function testFindAllwithNoNextPage() {
          $this->getServer()->enqueue((string) $this->getMockResponse('findAllwithNoNextPage'));
          $response = $this->accounts->findAll();
          $this->assertEquals(3, count($response->body));
          $this->assertFalse($response->hasNextPage());
      }
}
