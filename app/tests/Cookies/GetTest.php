<?php
namespace Tests\Cookies;

use Netsilik\Lib\Cookies;
use PHPUnit\Framework\TestCase;

class GetTest extends TestCase
{
	private $_cookies;

	public function setUp()
	{
		$_COOKIE['First'] = 'c29tZSB2YWx1ZQ=='; // some value
		$_COOKIE['Second'] = 'c29tZSBvdGhlciB2YWx1ZQ=='; // some other value
		$this->_cookies = Cookies::getInstance();
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_getNonExisting_returnsNull()
    {
		$this->assertNUll($this->_cookies->get('nonExisting'));
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_getExistingCookie_returnsCorrectValue()
    {
		$this->assertEquals('some value', $this->_cookies->get('First'));
	}
}
