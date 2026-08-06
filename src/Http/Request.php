<?php

declare(strict_types=1);

namespace Pietroagazzi\Mechanic\Http;

use Override;
use Psr\Http\Message\{RequestInterface, UriInterface};

/**
 * PSR-7 compliant HTTP request object
 *
 * @see https://www.php-fig.org/psr/psr-7/
 *
 * @author Pietro Agazzi <contact@agazzipietro.it>
 */
class Request extends AbstractMessage implements RequestInterface
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
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public static function createFromGlobals(): Request
    {
        return new Request($_POST, $_GET, $_COOKIE, $_SERVER);
    }

    /**
     * Get the path of the request
     *
     * @return string The path of the request
     */
    public function getPath(): string
    {
        $path = parse_url($this->getUri(), PHP_URL_PATH);

        return is_string($path) ? $path : '/';
    }

    /**
     * Get the URI of the request
     *
     * @return string The URI of the request
     */
    public function getUri(): UriInterface
    {
        return $this->stringFromServer('REQUEST_URI', '/');
    }

    /**
     * Get the HTTP method of the request
     *
     * @return string The HTTP method of the request
     */
    public function getMethod(): string
    {
        return $this->stringFromServer('REQUEST_METHOD', 'GET');
    }

    /**
     * Get the body of the request
     *
     * @return array<array-key, mixed> The body of the request
     */
    #[Override]
    public function getBody(): array
    {
        return $this->request;
    }

    /**
     * Get the query parameters of the request
     *
     * @return array<array-key, mixed> The query parameters of the request
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * Get the cookies of the request
     *
     * @return array<array-key, mixed> The cookies of the request
     */
    public function getCookies(): array
    {
        return $this->cookies;
    }

    /**
     * Get the server variables of the request
     *
     * @return array<array-key, mixed> The server variables of the request
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
     * Get the value of a query parameter
     *
     * Non-scalar values (e.g. nested arrays sent by the client) fall back to
     * the default, since this accessor is contractually string-typed.
     *
     * @param string $key The name of the parameter
     * @param string|null $default The default value if the parameter is not set
     */
    private function stringFromServer(string $key, string $default): string
    {
        $value = $this->server[$key] ?? null;

        return is_string($value) ? $value : $default;
    }
}
