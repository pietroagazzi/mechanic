<?php

declare(strict_types=1);

namespace Pietroagazzi\Mechanic\Http;

use Psr\Http\Message\StreamInterface;
use Override;
use Psr\Http\Message\ResponseInterface;

/**
 * PSR-7 compliant HTTP request object
 *
 * @see https://www.php-fig.org/psr/psr-7/
 *
 * @author Pietro Agazzi <contact@agazzipietro.it>
 */
class Response extends AbstractMessage implements ResponseInterface
{
    use ResponseStatusCodeTrait {
        ResponseStatusCodeTrait::text as public getStatusCodeText;
    }

    /**
     * TODO: Add parent call
     *
     * @param string|null $content The content of the response
     * @param int $status The status code of the response
     * @param array<string, string> $headers The headers of the response
     */
    public function __construct(
        private ?string $content = null,
        private int $status = 200,
        private string $reasonPhrase = '',
        private array $headers = []
    ) {

    }

    #[Override]
    public function withProtocolVersion(string $version): static
    {
        return parent::withProtocolVersion($version);
    }

    #[Override]
    public function withHeader(string $name, mixed $value): static
    {
        return parent::withHeader($name, $value);
    }

    #[Override]
    public function withAddedHeader(string $name, mixed $value): static
    {
        return parent::withAddedHeader($name, $value);
    }

    #[Override]
    public function withoutHeader(string $name): static
    {
        return parent::withoutHeader($name);
    }

    #[Override]
    public function withBody(StreamInterface $body): static
    {
        return parent::withBody($body);
    }

    /**
     * @inheritdoc
     */
    public function getStatusCode(): int
    {
        return $this->status;
    }

    /**
     * @inheritdoc
     */
    public function withStatus(int $code, string $reasonPhrase = ''): static
    {
        return clone($this, [
            'status' => $code,
            'reasonPhrase' => $reasonPhrase ?: self::getStatusCodeText($code)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    /**
     * Send the content fo the current web response
     *
     * @return $this;
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
     *
     * @return string|null The content of the response
     */
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
    #[Override]
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
            header("$name: $value", response_code: $this->getStatusCode());
        }

        // Send the status code
        header(
            sprintf(
                'HTTP/%s %s %s',
                '1.1',
                $this->getStatusCode(),
                $this->reasonPhrase
            ),
            response_code: $this->getStatusCode(),
        );

        return $this;
    }
}
