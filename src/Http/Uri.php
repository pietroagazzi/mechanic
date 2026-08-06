<?php

declare(strict_types=1);

namespace Pietroagazzi\Mechanic\Http;

use _PHPStan_88a9c7bcc\Psr\Http\Message\UriInterface;
use Psr\Http\Message\UriInterface;
use InvalidArgumentException;
use Override;

final class Uri implements UriInterface
{
    private string $scheme;
    private string $host;
    private ?int $port;
    private string $user;
    private ?string $pass;
    private string $path;
    private string $query;
    private string $fragment;

    /**
     * @var array<string, int> Standard ports for common schemes
     */
    private const STANDARD_PORTS = [
        'http' => 80,
        'https' => 443,

    ];

    final public function __construct(string $uri)
    {
        $this->parseUri($uri);
    }

    #[Override]
    public function getScheme(): string
    {
        return $this->scheme;
    }

    #[Override]
    public function getHost(): string
    {
        return $this->host;
    }

    #[Override]
    public function getPort(): ?int
    {
        $standardPort = self::STANDARD_PORTS[$this->getScheme()] ?? null;

        return $this->port === $standardPort ? null : $this->port;
    }

    #[Override]
    public function getUserInfo(): string
    {
        $userInfo = $this->user;

        if ($this->pass !== null) {
            $userInfo .= ':' . $this->pass;
        }

        return $userInfo;
    }

    #[Override]
    public function getPath(): string
    {
        return $this->path;
    }

    #[Override]
    public function getQuery(): string
    {
        return $this->query;
    }

    #[Override]
    public function getFragment(): string
    {
        return $this->fragment;
    }

    private function parseUri(string $uri): void
    {
        $parsed = parse_url($uri);

        if ($parsed === false) {
            throw new InvalidArgumentException("Invalid URI: $uri");
        }

        $this->scheme = isset($parsed['scheme']) ? strtolower($parsed['scheme']) : '';
        $this->host = isset($parsed['host']) ? strtolower($parsed['host']) : '';
        $this->port = isset($parsed['port']) ? (int)$parsed['port'] : null;
        $this->user = $parsed['user'] ?? '';
        $this->pass = $parsed['pass'] ?? null;
        $this->path = $parsed['path'] ?? '';
        $this->query = $parsed['query'] ?? '';
        $this->fragment = $parsed['fragment'] ?? '';
    }

    #[Override]
    public function getAuthority(): string
    {
        $authority = $this->getHost();
        $userInfo = $this->getUserInfo();

        if ($userInfo !== '') {
            $authority = $userInfo . '@' . $authority;
        }

        $port = $this->getPort();

        if ($port !== null) {
            $authority .= ':' . $port;
        }

        return $authority;
    }

    #[Override]
    public function withScheme(string $scheme): Uri
    {
        $scheme = strtolower($scheme);

        // TODO: Should be validated?

        return clone($this, [
            'scheme' => $scheme,
        ]);
    }

    #[Override]
    public function withUserInfo(
        string                         $user,
        #[\SensitiveParameter] ?string $password = null): Uri
    {
        return clone($this, [
            'user' => $user,
            'pass' => $password,
        ]);
    }

    #[Override]
    public function withHost(string $host): Uri
    {
        $host = strtolower($host);

        return clone($this, [
            'host' => $host,
        ]);
    }

    #[Override]
    public function withPort(?int $port): Uri
    {
        return clone($this, [
            'port' => $port,
        ]);
    }

    #[Override]
    public function withPath(string $path): Uri
    {
        return clone($this, [
            'path' => $path,
        ]);
    }

    #[Override]
    public function withQuery(string $query): Uri
    {
        return clone($this, [
            'query' => $query,
        ]);
    }

    #[Override]
    public function withFragment(string $fragment): Uri
    {
        return clone($this, [
            'fragment' => $fragment,
        ]);
    }

    public function __toString(): string
    {
        $uri = '';

        $scheme = $this->getScheme();

        if ($scheme !== '') {
            $uri .= $scheme . ':';
        }

        $authority = $this->getAuthority();

        if ($authority !== '') {
            $uri .= '//' . $authority;
        }

        $path = $this->getPath();

        if ($authority !== '' && $path !== '' && !str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        if ($authority === '' && str_starts_with($path, '//')) {
            $path = '/' . ltrim($path, '/');
        }

        $uri .= $path;

        $query = $this->getQuery();

        if ($query !== '') {
            $uri .= '?' . $query;
        }

        $fragment = $this->getFragment();

        if ($fragment !== '') {
            $uri .= '#' . $fragment;
        }

        return $uri;
    }
}
