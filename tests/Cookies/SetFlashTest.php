<?php
namespace Tests\Cookies;

use Netsilik\Lib\Cookies;
use PHPUnit\Framework\TestCase;

class SetFlashTest extends TestCase
{
	private $_cookies;
	
	public static function setUpBeforeClass()
	{
		self::assertTrue(function_exists('xdebug_get_headers'));
	}

	public function setUp()
	{
		$this->_cookies = Cookies::getInstance();
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_setFlash_returnsTrueAndSetsCookieNamedFlash()
    {
		$this->assertTrue($this->_cookies->setFlash('some value'));
		$this->assertEquals([
			0 => 'Set-Cookie: flash=c29tZSB2YWx1ZQ%3D%3D' // some value
		], xdebug_get_headers());
	}
}
