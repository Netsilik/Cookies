<?php
namespace Tests\Cookies;

use Netsilik\Cookies\Cookies;
use PHPUnit\Framework\TestCase;

class GetTest extends TestCase
{
	private $_cookies;

	public function setUp() : void
	{
		$_COOKIE['First'] = 'some value'; // some value
		$_COOKIE['Second'] = 'some other value'; // some other value
		$this->_cookies = new Cookies();
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_getNonExisting_returnsNull()
    {
		$this->assertNull($this->_cookies->get('nonExisting'));
	}
	
	/**
     * @runInSeparateProcess
     */
	public function test_getExistingCookie_returnsCorrectValue()
    {
		$this->assertEquals('some value', $this->_cookies->get('First'));
	}
}
