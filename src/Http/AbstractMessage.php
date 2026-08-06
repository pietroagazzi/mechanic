<?php

declare(strict_types=1);

namespace Pietroagazzi\Mechanic\Http;

use InvalidArgumentException;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Abstract class implementing the PSR-7 MessageInterface
 *
 * @see https://www.php-fig.org/psr/psr-7/
 *
 * @author Pietro Agazzi <contact@agazzipietro.it>
 */
abstract class AbstractMessage implements MessageInterface
{
    /**
     * @var array<string, list<string>> The HTTP headers
     */
    private array $headerNames = [];

    /**
     * @param array<string, string|string[]> $headers
     */
    protected function __construct(
        private StreamInterface $body,
        private array $headers = [],
        private string $protocolVersion = '1.1',
    ) {
        $this->assertValidProtocolVersion($protocolVersion);

        foreach ($headers as $name => $value) {
            $this->setHeader($name, $value);
        }
    }

    /**
     * @inheritdoc
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * @inheritdoc
     */
    public function withProtocolVersion(string $version): static
    {
        $this->assertValidProtocolVersion($version);

        return clone($this, [
            'protocolVersion' => $version,
        ]);
    }

    /**
     * @inheritdoc
     *
     * @return array<string, list<string>>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @inheritdoc
     */
    public function hasHeader(string $name): bool
    {
        return isset($this->headerNames[strtolower($name)]);
    }

    /**
     * @inheritdoc
     *
     * @return list<string>
     */
    public function getHeader(string $name): array
    {
        $originalName = $this->headerNames[strtolower($name)] ?? null;

        return null === $originalName ? [] : ($this->headers[$originalName] ?? []);
    }

    /**
     * @inheritdoc
     */
    public function getHeaderLine(string $name): string
    {
        return implode(',', $this->getHeader($name));
    }

    /**
     * @inheritdoc
     *
     * @param string|string[] $value
     */
    public function withHeader(string $name, mixed $value): static
    {
        $message = clone $this;
        $message->setHeader($name, $value);

        return $message;
    }

    /**
     * @inheritdoc
     *
     * @param string|string[] $value
     */
    public function withAddedHeader(string $name, mixed $value): static
    {
        $this->assertValidHeaderName($name);
        $values = $this->normalizeHeaderValues($value);

        $message = clone $this;
        $normalizedName = strtolower($name);
        $originalName = $message->headerNames[$normalizedName] ?? $name;

        $message->headerNames[$normalizedName] = $originalName;
        $message->headers[$originalName] = [
            ...($message->headers[$originalName] ?? []),
            ...$values,
        ];

        return $message;
    }

    /**
     * @inheritdoc
     */
    public function withoutHeader(string $name): static
    {
        $message = clone $this;
        $normalizedName = strtolower($name);
        $originalName = $message->headerNames[$normalizedName] ?? null;

        if (null !== $originalName) {
            unset($message->headers[$originalName], $message->headerNames[$normalizedName]);
        }

        return $message;
    }

    /**
     * @inheritdoc
     */
    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    /**
     * @inheritdoc
     */
    public function withBody(StreamInterface $body): static
    {
        $message = clone $this;
        $message->body = $body;

        return $message;
    }

    /**
     * @param string $version The HTTP protocol version
     *
     * @throws InvalidArgumentException If the HTTP protocol version is invalid
     */
    private function assertValidProtocolVersion(string $version): void
    {
        if (1 !== preg_match('/^\d+(?:\.\d+)?$/D', $version)) {
            throw new InvalidArgumentException(sprintf('Invalid HTTP protocol version "%s".', $version));
        }
    }

    /**
     * @param string $name The HTTP header name
     *
     * @throws InvalidArgumentException If the HTTP header name is invalid
     */
    private function assertValidHeaderName(string $name): void
    {
        if (1 !== preg_match('/^[!#$%&\'*+.^_`|~0-9A-Za-z-]+$/D', $name)) {
            throw new InvalidArgumentException(sprintf('Invalid HTTP header name "%s".', $name));
        }
    }

    /**
     * Normalizes header values to an array of strings, ensuring they are valid and trimmed.
     *
     * @param mixed $value The header value(s) to normalize
     * @return array<string> The normalized header values
     * @throws InvalidArgumentException If any header value is not a string
     * @throws InvalidArgumentException If any header value contains control characters
     */
    private function normalizeHeaderValues(mixed $value): array
    {
        $values = is_array($value) ? $value : [$value];
        $normalizedValues = [];

        foreach ($values as $headerValue) {
            if (!is_string($headerValue)) {
                throw new InvalidArgumentException('HTTP header values must be strings.');
            }

            if (1 !== preg_match('/^[\x09\x20-\x7E\x80-\xFF]*$/D', $headerValue)) {
                throw new InvalidArgumentException('HTTP header values must not contain control characters.');
            }

            $normalizedValues[] = trim($headerValue, " \t");
        }

        return $normalizedValues;
    }

    /**
     * Sets a header in the message, replacing any existing values for that header.
     *
     * @param string $name The HTTP header name
     * @param mixed $value The HTTP header value(s)
     */
    private function setHeader(string $name, mixed $value): void
    {
        $this->assertValidHeaderName($name);
        $values = $this->normalizeHeaderValues($value);
        $normalizedName = strtolower($name);
        $previousName = $this->headerNames[$normalizedName] ?? null;

        if (null !== $previousName && $previousName !== $name) {
            unset($this->headers[$previousName]);
        }

        $this->headerNames[$normalizedName] = $name;
        $this->headers[$name] = $values;
    }
}
