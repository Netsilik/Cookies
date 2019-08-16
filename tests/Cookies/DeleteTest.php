<?php
namespace Tests\Cookies;

use Netsilik\Cookies\Cookies;
use PHPUnit\Framework\TestCase;

class DeleteTest extends TestCase
{
	 public static function setUpBeforeClass(): void
	{
		self::assertTrue(function_exists('xdebug_get_headers'));
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_getNonExisting_returnsNull()
    {
		$cookies = new Cookies();
		
		$this->assertFalse($cookies->delete('nonExisting'));
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_getExistingCookie_returnsTrueAndHeadersCorrectlyUpdated()
    {
		$_COOKIE['First'] = 'some+other+value'; // some value
		$cookies = new Cookies();
		
		$this->assertTrue($cookies->set('First', 'some other value', false, '/', 'example.com'));
		$this->assertEquals([
			0 => 'Set-Cookie: First=some+other+value; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/; domain=example.com; SameSite=Any' // deleted
		], xdebug_get_headers());
		
		
		$this->assertTrue($cookies->delete('First', '/', 'example.com'));
		$this->assertEquals([
			0 => 'Set-Cookie: First=deleted; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/; domain=example.com; SameSite=Any' // deleted
		], xdebug_get_headers());
	}
}
