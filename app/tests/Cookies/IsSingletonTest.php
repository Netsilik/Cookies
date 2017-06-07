<?php
namespace Tests\Cookies;

use Netsilik\Lib\Cookies;
use PHPUnit\Framework\TestCase;

class IsSingletonTest extends TestCase
{
	public function test_getInstance_instanceCreatedUponFirstCall()
    {
		$cookies = Cookies::getInstance();
		$this->assertInstanceOf(Cookies::class, $cookies);
	}
	
	public function test_newInstantiation_error()
    {
		$this->expectException(\Error::class);
		
		try {
			$cookies = new Cookies();
		} catch (\Error $e) {
			$this->assertEquals($e->getMessage(), "Call to private ".Cookies::class."::__construct() from context '".__CLASS__."'");
			throw $e;
		}
	}
	
	public function test_cloneInstantiation_error()
    {
		$this->expectException(\Error::class);
		
		$cookies = Cookies::getInstance();
		$this->assertInstanceOf(Cookies::class, $cookies);
		
		try {
			$clone = clone $cookies;
		} catch (\Error $e) {
			$this->assertEquals($e->getMessage(), "Call to private ".Cookies::class."::__clone() from context '".__CLASS__."'");
			throw $e;
		}
	}
}
