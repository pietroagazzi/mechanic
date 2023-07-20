<?php

namespace Pietroagazzi\Mechanic\Router;

use Closure;

readonly class Route
{
	public function __construct(
		private string  $method,
		private string  $path,
		private Closure $handler
	)
	{
	}

	public function getMethod(): string
	{
		return $this->method;
	}

	public function getPath(): string
	{
		return $this->path;
	}

	public function invoke(): mixed
	{
		return call_user_func($this->handler);
	}
}