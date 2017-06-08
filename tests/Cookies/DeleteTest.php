<?php
namespace Tests\Cookies;

use Netsilik\Lib\Cookies;
use PHPUnit\Framework\TestCase;

class DeleteTest extends TestCase
{
	public static function setUpBeforeClass()
	{
		self::assertTrue(function_exists('xdebug_get_headers'));
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_getNonExisting_returnsNull()
    {
		$cookies = Cookies::getInstance();
		
		$this->assertFalse($cookies->delete('nonExisting'));
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_getExistingCookie_returnsTrueAndHeadersCorrectlyUpdated()
    {
		$_COOKIE['First'] = 'c29tZSB2YWx1ZQ=='; // some value
		$cookies = Cookies::getInstance();
		
		$this->assertTrue($cookies->set('First', 'some other value', false, '/', 'example.com'));
		$this->assertEquals([
			0 => 'Set-Cookie: First=c29tZSBvdGhlciB2YWx1ZQ%3D%3D; path=/; domain=example.com' // deleted
		], xdebug_get_headers());
		
		
		$this->assertTrue($cookies->delete('First', '/', 'example.com'));
		$this->assertEquals([
			0 => 'Set-Cookie: First=deleted; expires=Thu, 01 Jan 1970 01:00:00 GMT; path=/; domain=example.com' // deleted
		], xdebug_get_headers());
	}
}
