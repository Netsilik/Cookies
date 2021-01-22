<?php
namespace Netsilik\Cookies;

/**
 * @package       Netsilik/Cookies
 * @copyright (c) Netsilik (http://netsilik.nl)
 * @license       EUPL-1.1 (European Union Public Licence, v1.1)
 */

use InvalidArgumentException;
use Netsilik\Cookies\Interfaces\iCookies;


/**
 * Cookies
 * Fix the broken cookie handling functions native to PHP
 */
class Cookies implements iCookies
{
	
	/**
	 * @var array $_cookies The cookies to send
	 */
	private $_cookies;
	
	/**
	 * {@inheritDoc}
	 * @throws \InvalidArgumentException
	 */
	public function set(string $name, string $value, int $expire = null, string $path = '', string $domain = '', bool $secure = false, bool $httpOnly = false, $sameSite = 'any') : bool
	{
		$sameSite = ucfirst(strtolower($sameSite));
		if (!in_array($sameSite, ['None', 'Lax', 'Strict'])) {
			throw new InvalidArgumentException('Value for parameter $sameSite should be one of [None | Lax | Strict]');
		}
		
		$this->_cookies[ $name ]['value']    = $value;
		$this->_cookies[ $name ]['expire']   = $expire;
		$this->_cookies[ $name ]['path']     = $path;
		$this->_cookies[ $name ]['domain']   = $domain;
		$this->_cookies[ $name ]['secure']   = $secure;
		$this->_cookies[ $name ]['httpOnly'] = $httpOnly;
		$this->_cookies[ $name ]['sameSite'] = $sameSite;
		
		$_COOKIE[ $name ] = $this->_cookies[ $name ]['value'];
		
		return $this->_updateHeaders();
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function get(string $name)
	{
		if (!isset($_COOKIE[ $name ])) {
			return null;
		}
		
		return $_COOKIE[ $name ];
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function delete(string $name, string $path = '', string $domain = '') : bool
	{
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
			if (null !== $data['expire']) {
				$headerStr .= '; expires=' . gmdate('D, d M Y H:i:s', $data['expire']) . ' GMT';
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
			
			header($headerStr, $replace); // Replace all cookies headers on the first run, append after that
			$replace = false;
		}
		
		return true;
	}
}
