<?php

namespace Pietroagazzi\Mechanic\Http;

class Request
{
	/**
	 * Equivalent to $_POST
	 * @var array<string, string> $request
	 */
	private array $request;

	/**
	 * Equivalent to $_GET
	 * @var array<string, string> $query
	 */
	private array $query;

	/**
	 * Equivalent to $_COOKIE
	 * @var array<string, string> $cookies
	 */
	private array $cookies;

	/**
	 * Equivalent to $_SERVER
	 * @var array<string, string> $server
	 */
	private array $server;

	/**
	 * @param array<string, string> $request
	 * @param array<string, string> $query
	 * @param array<string, string> $cookies
	 * @param array<string, string> $server
	 */
	public function __construct(array $request, array $query, array $cookies, array $server)
	{
		$this->request = $request;
		$this->query = $query;
		$this->cookies = $cookies;
		$this->server = $server;
	}

	/**
	 * Create a new Request object from the global variables
	 */
	public static function createFromGlobals(): Request
	{
		return new Request($_POST, $_GET, $_COOKIE, $_SERVER);
	}

	public function getPath(): string
	{
		return parse_url($this->getUri(), PHP_URL_PATH);
	}

	/**
	 * Get the URI of the request
	 */
	public function getUri(): string
	{
		return $this->server['REQUEST_URI'];
	}

	/**
	 * Get the HTTP method of the request
	 */
	public function getMethod(): string
	{
		return $this->server['REQUEST_METHOD'];
	}

	/**
	 * Get the request body
	 * @return array<string, string>
	 */
	public function getBody(): array
	{
		return $this->request;
	}

	/**
	 * Get the query string
	 * @return array<string, string>
	 */
	public function getQuery(): array
	{
		return $this->query;
	}

	/**
	 * Get the cookies
	 * @return array<string, string>
	 */
	public function getCookies(): array
	{
		return $this->cookies;
	}

	/**
	 * Get the server variables
	 * @return array<string, string>
	 */
	public function getServer(): array
	{
		return $this->server;
	}

	/**
	 * Get the value of a request parameter
	 * @param string $key The name of the parameter
	 * @param string|null $default The default value if the parameter is not set
	 */
	public function get(string $key, string $default = null): ?string
	{
		return $this->request[$key] ?? $default;
	}
}