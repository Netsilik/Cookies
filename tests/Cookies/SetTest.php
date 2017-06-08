<?php
namespace Tests\Cookies;

use Netsilik\Lib\Cookies;
use PHPUnit\Framework\TestCase;

class SetTest extends TestCase
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
	public function test_setWithDefaultParameterValues_returnsTrueAndCorrectHeadersSet()
    {
		$this->assertTrue($this->_cookies->set('Test', 'some value'));
		$this->assertEquals([
			0 => 'Set-Cookie: Test=c29tZSB2YWx1ZQ%3D%3D'
		], xdebug_get_headers());
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_setWithExpireTime_returnsTrueAndCorrectHeadersSet()
    {
		$this->assertTrue($this->_cookies->set('Test', 'some value', 1577836800));
		$this->assertEquals([
			0 => 'Set-Cookie: Test=c29tZSB2YWx1ZQ%3D%3D; expires=Wed, 01 Jan 2020 01:00:00 GMT'
		], xdebug_get_headers());
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_setWithPath_returnsTrueAndCorrectHeadersSet()
    {
		$this->assertTrue($this->_cookies->set('Test', 'some value', false, '/test/'));
		$this->assertEquals([
			0 => 'Set-Cookie: Test=c29tZSB2YWx1ZQ%3D%3D; path=/test/'
		], xdebug_get_headers());
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_setWithDomain_returnsTrueAndCorrectHeadersSet()
    {
		$this->assertTrue($this->_cookies->set('Test', 'some value', false, '', 'example.com'));
		$this->assertEquals([
			0 => 'Set-Cookie: Test=c29tZSB2YWx1ZQ%3D%3D; domain=example.com'
		], xdebug_get_headers());
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_setWithSecureFlag_returnsTrueAndCorrectHeadersSet()
    {
		$this->assertTrue($this->_cookies->set('Test', 'some value', false, '', '', true));
		$this->assertEquals([
			0 => 'Set-Cookie: Test=c29tZSB2YWx1ZQ%3D%3D; secure'
		], xdebug_get_headers());
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_setWithHttpOnlyFlag_returnsTrueAndCorrectHeadersSet()
    {
		$this->assertTrue($this->_cookies->set('Test', 'some value', false, '', '', false, true));
		$this->assertEquals([
			0 => 'Set-Cookie: Test=c29tZSB2YWx1ZQ%3D%3D; httponly'
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
			0 => 'Set-Cookie: First=c29tZSB2YWx1ZQ%3D%3D', // some value
			1 => 'Set-Cookie: Second=c29tZSBvdGhlciB2YWx1ZQ%3D%3D',  // some other value
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
			0 => 'Set-Cookie: Test=c2Vjb25kIGNvb2tpZSB2YWx1ZQ%3D%3D' // second cookie value
		], xdebug_get_headers());
	}
}
