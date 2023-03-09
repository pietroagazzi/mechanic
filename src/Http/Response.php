<?php

namespace Pietroagazzi\Mechanic\Http;

class Response
{
    use ResponseStatusCodeTrait {
        ResponseStatusCodeTrait::text as public getStatusCodeText;
    }

    /**
     * @var string|null $content
     */
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
     * @return void
     */
    public function send(): void
    {
        $this->sendHeaders();
        $this->sendContent();
    }

    /**
     * Send the headers of the response to the client
     * @return $this
     */
    public function sendHeaders(): static
    {
        foreach ($this->headers as $name => $value) {
            header("$name: $value", response_code: $this->getStatus());
        }

        // Set the status code
        header("HTTP/1.1 {$this->getStatus()} {$this->getStatusCodeText($this->getStatus())}", true, $this->getStatus());

        return $this;
    }

    /**
     * Send the content fo the current web response
     * @return $this
     */
    public function sendContent(): static
    {
        ob_start();

        echo $this->getContent();

        ob_end_flush();

        return $this;
    }

    /**
     * Get the content of the response
     * @return int
     */
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
     * Get the content of the response
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Set the content of the response
     * @param string|null $content
     * @return $this
     */
    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the headers of the response
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Set the headers of the response
     * @param array<string, string> $headers
     * @return $this
     */
    public function setHeaders(array $headers): static
    {
        $this->headers = $headers;

        return $this;
    }
}