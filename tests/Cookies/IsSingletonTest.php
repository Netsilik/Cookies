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
	
	public function test_callClose_andExpectNothing()
    {
		$cookies = Cookies::getInstance();
		$this->assertInstanceOf(Cookies::class, $cookies);
		
		$this->callInaccessibleMethod($cookies, '__clone');
	}
	
    /**
     * Call a private or protected method
     * @param object $instance The instance of the class to call the specified method on
     * @param string $method The method to call on the provided instance
     * @param array $parameters The parameters to pass into the specified method
     * @return mixed
     */
    protected function callInaccessibleMethod($instance, $method, array $parameters = [])
    {
        $class = new ReflectionClass(get_class($instance));
        $method = $class->getMethod($method);
        $method->setAccessible(true);

        return $method->invokeArgs($instance, $parameters);
    }
}
