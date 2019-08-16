<?php
namespace Tests\Cookies;

use Netsilik\Cookies\Cookies;
use PHPUnit\Framework\TestCase;

class SetTest extends TestCase
{
	private $_cookies;
	
	public static function setUpBeforeClass() : void
	{
		self::assertTrue(function_exists('xdebug_get_headers'));
	}

	public function setUp() : void
	{
		$this->_cookies = new Cookies();
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_setWithDefaultParameterValues_returnsTrueAndCorrectHeadersSet()
    {
		$this->assertTrue($this->_cookies->set('Test', 'some value'));
		$this->assertEquals([
			0 => 'Set-Cookie: Test=some+value; SameSite=Any'
		], xdebug_get_headers());
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_setWithExpireTime_returnsTrueAndCorrectHeadersSet()
    {
		$this->assertTrue($this->_cookies->set('Test', 'some value', 1577836800));
		$this->assertEquals([
			0 => 'Set-Cookie: Test=some+value; expires=Wed, 01 Jan 2020 00:00:00 GMT; SameSite=Any'
		], xdebug_get_headers());
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_setWithPath_returnsTrueAndCorrectHeadersSet()
    {
		$this->assertTrue($this->_cookies->set('Test', 'some value', false, '/test/'));
		$this->assertEquals([
			0 => 'Set-Cookie: Test=some+value; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/test/; SameSite=Any'
		], xdebug_get_headers());
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_setWithDomain_returnsTrueAndCorrectHeadersSet()
    {
		$this->assertTrue($this->_cookies->set('Test', 'some value', false, '', 'example.com'));
		$this->assertEquals([
			0 => 'Set-Cookie: Test=some+value; expires=Thu, 01 Jan 1970 00:00:00 GMT; domain=example.com; SameSite=Any'
		], xdebug_get_headers());
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_setWithSecureFlag_returnsTrueAndCorrectHeadersSet()
    {
		$this->assertTrue($this->_cookies->set('Test', 'some value', false, '', '', true));
		$this->assertEquals([
			0 => 'Set-Cookie: Test=some+value; expires=Thu, 01 Jan 1970 00:00:00 GMT; secure; SameSite=Any'
		], xdebug_get_headers());
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_setWithHttpOnlyFlag_returnsTrueAndCorrectHeadersSet()
    {
		$this->assertTrue($this->_cookies->set('Test', 'some value', false, '', '', false, true));
		$this->assertEquals([
			0 => 'Set-Cookie: Test=some+value; expires=Thu, 01 Jan 1970 00:00:00 GMT; httponly; SameSite=Any'
		], xdebug_get_headers());
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_setMultipleCookiesWithDifferentNames_returnsTrueAndTwoCookiesSet()
    {
		$this->assertTrue($this->_cookies->set('First', 'some value'));
		$this->assertTrue($this->_cookies->set('Second', 'some other value'));
		$this->assertEquals([
			0 => 'Set-Cookie: First=some+value; SameSite=Any', // some value
			1 => 'Set-Cookie: Second=some+other+value; SameSite=Any',  // some other value
		], xdebug_get_headers());
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_setMultipleCookiesWithSameNames_returnsTrueAndOnlySinlgeCookieSet()
    {
		$this->assertTrue($this->_cookies->set('Test', 'first cookie value'));
		$this->assertTrue($this->_cookies->set('Test', 'second cookie value'));
		$this->assertEquals([
			0 => 'Set-Cookie: Test=second+cookie+value; SameSite=Any' // second cookie value
		], xdebug_get_headers());
	}
}
