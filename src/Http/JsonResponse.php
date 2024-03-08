<?php

namespace Pietroagazzi\Mechanic\Http;

use function json_encode;

/**
 * A class that represents a JSON response
 *
 * @author Pietro Agazzi <contact@agazzipietro.it>
 */
class JsonResponse extends Response
{
	public function __construct(
		private readonly mixed $data,
		private readonly int   $status = 200,
		private array          $headers = []
	)
	{
		$this->headers['Content-Type'] ??= 'application/json';

		parent::__construct($this->encode(), $this->status, $this->headers);
	}

	/**
	 * Encode the data to JSON
	 */
	private function encode(): string
	{
		return json_encode($this->data, JSON_THROW_ON_ERROR);
	}
}