<?php
namespace Netsilik\Cookies\Interfaces;

/**
 * @package       Netsilik/Cookies
 * @copyright (c) Netsilik (http://netsilik.nl)
 * @license       EUPL-1.1 (European Union Public Licence, v1.1)
 */


interface iCookies
{
	
	/**
	 * Set a cookie by name
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
	 * @param string $sameSite Optional value. Possible values: [any|'lax'|'strict']. In Any mode, the cookie beheviour is unrestricted. In lax mode,
	 *                         some cross-site usage is allowed. Specifically for GET request that changes the URL in the browser address bar. In the
	 *                         strict mode, the cookie is withheld with any cross-site usage. Even when the user follows a link to another website
	 *                         the cookie is not sent.
	 *
	 * @return bool            True if  php did not encounter any problems. This does not mean the cookie is accepted by the browser!
	 * @throws \InvalidArgumentException
	 */
	public function set(string $name, string $value, int $expire = null, string $path = '', string $domain = '', bool $secure = false, bool $httpOnly = false, $sameSite = 'any') : bool;
	
	/**
	 * get a cookie by name
	 *
	 * @param string $name the name of the cookie to return
	 *
	 * @return mixed an array with the cookie data on success, null if no cookie with name $name was found
	 */
	public function get(string $name);
	
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
	public function delete(string $name, string $path = '', string $domain = '') : bool;
}
