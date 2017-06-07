<?php
namespace Tests\Cookies;

use Netsilik\Lib\Cookies;
use PHPUnit\Framework\TestCase;

class GetFlashTest extends TestCase
{
	private $_cookies;
		
	public static function setUpBeforeClass()
	{
		self::assertTrue(function_exists('xdebug_get_headers'));
	}

	public function setUp()
	{
		$_COOKIE['flash'] = 'c29tZSB2YWx1ZQ=='; // some value
		$this->_cookies = Cookies::getInstance();
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_getFlash_returnsTrueAndDeletesCookieNamedFlash()
    {
		$this->assertEquals('some value', $this->_cookies->getFlash());
		$this->assertEquals([
			0 => 'Set-Cookie: flash=deleted; expires=Thu, 01 Jan 1970 01:00:00 GMT' // deleted
		], xdebug_get_headers());
	}
}
