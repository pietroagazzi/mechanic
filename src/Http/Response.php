<?php

namespace Pietroagazzi\Mechanic\Http;

class Response
{
	use ResponseStatusCodeTrait {
		ResponseStatusCodeTrait::text as public getStatusCodeText;
	}

	private ?string $content;

	/**
	 * @var int $status
	 */
	private int $status;

	/**
	 * @var array<string, string> $headers
	 */
	private array $headers;

	/**
	 * Response constructor.
	 * @param string|null $content Content of the response
	 * @param int $status Status code of the response
	 * @param array<string, string> $headers Headers of the response
	 */
	public function __construct(string $content = null, int $status = 200, array $headers = [])
	{
		$this->content = $content;
		$this->status = $status;
		$this->headers = $headers;
	}

	/**
	 * Send the response to the client
	 */
	public function send(): void
	{
		$this->sendHeaders();
		$this->sendContent();
	}

	/**
	 * Send the headers of the response to the client
	 */
	public function sendHeaders(): static
	{
		foreach ($this->headers as $name => $value) {
			header("$name: $value", response_code: $this->getStatus());
		}

		// Send the status code
		header(
			sprintf(
				'HTTP/%s %s %s',
				'1.1',
				$this->getStatus(),
				self::getStatusCodeText($this->getStatus())
			),
			response_code: $this->getStatus()
		);

		return $this;
	}

	public function getStatus(): int
	{
		return $this->status;
	}

	public function setStatus(int $status): static
	{
		$this->status = $status;

		return $this;
	}

	/**
	 * Send the content fo the current web response
	 */
	public function sendContent(): static
	{
		ob_start();

		echo $this->getContent();

		ob_end_flush();

		return $this;
	}

	public function getContent(): ?string
	{
		return $this->content;
	}

	public function setContent(?string $content): static
	{
		$this->content = $content;

		return $this;
	}

	/**
	 * @return array<string, string>
	 */
	public function getHeaders(): array
	{
		return $this->headers;
	}

	/**
	 * @param array<string, string> $headers
	 */
	public function setHeaders(array $headers): static
	{
		$this->headers = $headers;

		return $this;
	}
}