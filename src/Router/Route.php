<?php

namespace Pietroagazzi\Mechanic\Router;

readonly class Route
{
	public function __construct(
		private string $path,
	)
	{
	}

	public function getPath(): string
	{
		return $this->path;
	}
}