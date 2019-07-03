<?php
namespace Netsilik\Lib;

/**
 * @package       Scepino\Lib
 * @copyright (c) 2010-2016 Scepino (http://scepino.com)
 * @license       EUPL-1.1 (European Union Public Licence, v1.1)
 */

/**
 * Fix the broken cookie handling functions native to PHP
 * Singleton
 * Controls all cookies
 */
class Cookies
{
	
	/**
	 * @var Netsilik\Lib\Cookies $_instance
	 */
	private static $_instance;
	
	/**
	 * @var array $_cookies The cookies to send
	 */
	private $_cookies;
	
	/**
	 * @var Netsilik\Lib\Cookies $_instance The cookies received
	 */
	private $_cookieData;
	
	/**
	 * Get the (static) instance of this object
	 *
	 * @return Object static instance of class Config
	 */
	public static function getInstance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new Cookies();
		}
		
		return self::$_instance;
	}
	
	/**
	 * set a cookie by name
	 *
	 * @param string $name     The name of the cookie.
	 * @param string $value    The value of the cookie.
	 * @param int    $expire   The time the cookie expires. This is a Unix timestamp so is in number of seconds since the epoch. If set to 0, or
	 *                         omitted, the cookie will expire at the end of the session (when the browser closes). Expire is compared to the
	 *                         client's time which can differ from server's time.
	 * @param string $path     The path on the server in which the cookie will be available on. If set to '/', the cookie will be available within
	 *                         the entire domain. If set to '/foo/', the cookie will only be available within the /foo/ directory and all
	 *                         sub-directories such as /foo/bar/ of domain. The default value is the current directory that the cookie is being set
	 *                         in.
	 * @param string $domain   The domain that the cookie is available. To make the cookie available on all subdomains of example.com then you'd set
	 *                         it to '.example.com'. The . is not required but makes it compatible with more browsers. Setting it to www.example.com
	 *                         will make the cookie only available in the www subdomain. Refer to tail matching in the » spec for details.
	 * @param bool   $secure   Indicates that the cookie should only be transmitted over a secure HTTPS connection from the client. When set to true,
	 *                         the cookie will only be set if a secure connection exists. On the server-side, it's on the programmer to send this
	 *                         kind of cookie only on secure connection (e.g. with respect to $_SERVER["HTTPS"]).
	 * @param bool   $httponly When true the cookie will be made accessible only through the HTTP protocol. This means that the cookie won't be
	 *                         accessible by scripting languages, such as JavaScript. This setting can effectively help to reduce identity theft
	 *                         through XSS attacks (although it is not supported by all browsers).
	 * @param bool   $sameSite Optional value. Possible values: [any|'lax'|'strict']. In Any mode, the cookie beheviour is unrestricted. In lax mode,
	 *                         some cross-site usage is allowed. Specifically for GET request that changes the URL in the browser address bar. In the
	 *                         strict mode, the cookie is withheld with any cross-site usage. Even when the user follows a link to another website
	 *                         the cookie is not sent.
	 *
	 * @return bool true on succes
	 * @note if this method returns true, php did not encounter any problems. This does not mean the cookie is accepted by the browser
	 */
	public function set($name, $value = '', $expire = false, $path = '', $domain = '', $secure = false, $httpOnly = false, $sameSite = 'any')
	{
		$this->_cookies[ $name ]['value']    = base64_encode($value);
		$this->_cookies[ $name ]['expire']   = $expire;
		$this->_cookies[ $name ]['path']     = $path;
		$this->_cookies[ $name ]['domain']   = $domain;
		$this->_cookies[ $name ]['secure']   = $secure;
		$this->_cookies[ $name ]['httpOnly'] = $httpOnly;
		$this->_cookies[ $name ]['sameSite'] = ($sameSite === 'strict' ? 'Strict' : ($sameSite === 'lax' ? 'Lax' : 'Any'));
		
		return $this->_updateHeaders();
	}
	
	/**
	 * get a cookie by name
	 *
	 * @param string $name the name of the cookie to return
	 *
	 * @return mixed an array with the cookie data on succes, null if no cookie with name $name was found
	 */
	public function get($name)
	{
		if (!isset($this->_cookieData[ $name ])) {
			return null;
		}
		
		return base64_decode($this->_cookieData[ $name ], true);
	}
	
	/**
	 * Delete a set cookie
	 *
	 * @param string $name   the name of the cookie to delete
	 * @param string $path   the path for the cookie to delete
	 * @param string $domain the name for the cookie to delete
	 *
	 * @return bool true on succes, false if there was no cookie on the client by the name $name
	 * @warning $path should be set equel to the $path used to set the cookie for the browser to actually delete the cookie
	 */
	public function delete($name, $path = '', $domain = '')
	{
		if (!isset($this->_cookieData[ $name ])) { // cookie not set on client -> no need to delete it
			return false;
		}
		if (!isset($this->_cookies[ $name ])) {
			$this->_cookies[ $name ]['secure']   = false;
			$this->_cookies[ $name ]['httpOnly'] = false;
			$this->_cookies[ $name ]['sameSite'] = 'Any';
			$this->_cookies[ $name ]['path']     = $path;
			$this->_cookies[ $name ]['domain']   = $domain;
		} else {
			if ($path <> '') {
				$this->_cookies[ $name ]['path'] = $path;
			}
			if ($domain <> '') {
				$this->_cookies[ $name ]['domain'] = $domain;
			}
		}
		$this->_cookies[ $name ]['value']  = 'deleted';
		$this->_cookies[ $name ]['expire'] = 0;
		
		return $this->_updateHeaders();
	}
	
	/**
	 * Send out the cookie headers
	 *
	 * @return bool true
	 */
	private function _updateHeaders()
	{
		$replace = true;
		foreach ($this->_cookies as $name => $data) {
			$headerStr = 'Set-Cookie: ' . $name . '=' . urlencode($data['value']);
			if ($data['expire'] !== false) {
				$headerStr .= '; expires=' . date('D, d M Y H:i:s', $data['expire']) . ' GMT';
			}
			if (!empty($data['path'])) {
				$headerStr .= '; path=' . $data['path'];
			}
			if (!empty($data['domain'])) {
				$headerStr .= '; domain=' . $data['domain'];
			}
			if ($data['secure']) {
				$headerStr .= '; secure';
			}
			if ($data['httpOnly']) {
				$headerStr .= '; httponly';
			}
			if ($data['sameSite']) {
				$headerStr .= '; SameSite=' . $data['sameSite'];
			}
			header($headerStr, $replace);
			$replace = false;
		}
		
		return true;
	}
	
	/**
	 * private constructor: prevent the use of 'new'
	 */
	final private function __construct()
	{
		$this->_cookieData = $_COOKIE;
		$_COOKIE           = ['This variable is managed by the Cookie Object'];
	}
	
	/**
	 * private __clone: prevent object cloning
	 */
	final private function __clone()
	{
		// deliberately left empty
	}
}	
