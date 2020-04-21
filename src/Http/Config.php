<?php

namespace Vimiso\Http;

use Exception;

class Config
{
    /**
     * The supported package versions and their API paths.
     *
     * @var array
     */
    const VERSIONS = [
        1 => 'v1',
    ];

    /**
     * The selected API version.
     *
     * @var int
     */
    protected $version = 1;

    /**
     * The base URI address.
     *
     * @var null|string
     */
    protected $baseUri = null;

    /**
     * The package name.
     *
     * @var null|string
     */
    protected $name = null;

    /**
     * The request timeout in seconds.
     *
     * @var int
     */
    protected $timeout = 30;

    /**
     * If given, set a base URI on construct.
     *
     * @param null|string $baseUri
     * @return void
     */
    public function __construct(?string $baseUri = null)
    {
        if (! empty($baseUri)) {
            $this->setBaseUri($baseUri);
        }
    }

    /**
     * Use the given package version if it's supported.
     *
     * @param int $version
     * @return $this
     * @throws \Exception
     */
    public function useVersion($version)
    {
        if (array_key_exists($version, $this::VERSIONS)) {
            $this->version = $version;
        } else {
            throw new Exception("API version [{$version}] is not supported");
        }

        return $this;
    }

    /**
     * Set the given base URI.
     *
     * @param string $baseUri
     * @return void
     */
    public function setBaseUri(string $baseUri): void
    {
        $this->baseUri = $baseUri;
    }

    /**
     * Set the given timeout in seconds.
     *
     * @param int $seconds
     * @return void
     */
    public function setTimeout(int $seconds): void
    {
        $this->timeout = $seconds;
    }

    /**
     * Set the package name.
     *
     * @param string $name
     * @return void
     */
    public function setPackageName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the base URI.
     *
     * @return null|string
     */
    public function getBaseUri(): ?string
    {
        return $this->baseUri;
    }

    /**
     * Get the timeout seconds.
     *
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Get the package headers to include in the request.
     *
     * @return array
     */
    public function getPackageHeaders(): array
    {
        return [
            'X-Package-Name' => $this->name,
            'X-Package-Version' => $this->version,
        ];
    }

    /**
     * Get the package name.
     *
     * @return null|string
     */
    public function getPackageName(): ?string
    {
        return $this->name;
    }

    /**
     * Get the package version number.
     *
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * Get the package version's path.
     *
     * @return string
     */
    public function getVersionPath(): string
    {
        return $this::VERSIONS[$this->version];
    }
}
