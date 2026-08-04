<?php

declare(strict_types=1);

namespace Pietroagazzi\Mechanic\Http;

/**
 * HTTP request object
 *
 * @todo: Should implement PSR-7 RequestInterface
 *
 * @author Pietro Agazzi <contact@agazzipietro.it>
 */
class Request
{
    /**
     * Keys are widened to array-key because superglobals can carry numeric keys
     * (e.g. a query string like "?0=foo").
     *
     * @param array<array-key, mixed> $request Equivalent to $_POST
     * @param array<array-key, mixed> $query Equivalent to $_GET
     * @param array<array-key, mixed> $cookies Equivalent to $_COOKIE
     * @param array<array-key, mixed> $server Equivalent to $_SERVER
     */
    public function __construct(
        private readonly array $request,
        private readonly array $query,
        private readonly array $cookies,
        private readonly array $server,
    ) {
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
        $path = parse_url($this->getUri(), PHP_URL_PATH);

        return is_string($path) ? $path : '/';
    }

    /**
     * Get the URI of the request
     */
    public function getUri(): string
    {
        return $this->stringFromServer('REQUEST_URI', '/');
    }

    /**
     * Get the HTTP method of the request
     */
    public function getMethod(): string
    {
        return $this->stringFromServer('REQUEST_METHOD', 'GET');
    }

    /**
     * Get the request body
     *
     * @return array<array-key, mixed>
     */
    public function getBody(): array
    {
        return $this->request;
    }

    /**
     * Get the query string
     *
     * @return array<array-key, mixed>
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * Get the cookies
     *
     * @return array<array-key, mixed>
     */
    public function getCookies(): array
    {
        return $this->cookies;
    }

    /**
     * Get the server variables
     *
     * @return array<array-key, mixed>
     */
    public function getServer(): array
    {
        return $this->server;
    }

    /**
     * Get the value of a request parameter
     *
     * Non-scalar values (e.g. nested arrays sent by the client) fall back to
     * the default, since this accessor is contractually string-typed.
     *
     * @param string $key The name of the parameter
     * @param string|null $default The default value if the parameter is not set
     */
    public function get(string $key, ?string $default = null): ?string
    {
        $value = $this->request[$key] ?? null;

        return is_scalar($value) ? (string) $value : $default;
    }

    /**
     * Read a server variable as a string, falling back when it is absent or not a string
     */
    private function stringFromServer(string $key, string $default): string
    {
        $value = $this->server[$key] ?? null;

        return is_string($value) ? $value : $default;
    }
}
