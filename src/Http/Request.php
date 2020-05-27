<?php

namespace Vimiso\Http;

use Throwable;
use GuzzleHttp\Client;
use Vimiso\Http\Exceptions\RequestException;
use GuzzleHttp\Exception\RequestException as GuzzleException;

abstract class Request
{
    /**
     * The Guzzle client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * The configuration options.
     *
     * @var \Vimiso\Http\Config
     */
    protected $config;

    /**
     * The request mode.
     *
     * Supported: json, form_params, multipart.
     *
     * @var string
     */
    protected $mode;

    /**
     * The request method.
     *
     * @var string
     */
    protected $method;

    /**
     * The request headers.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * The request params.
     *
     * @var array
     */
    protected $params = [];

    /**
     * The request path.
     *
     * @var string
     */
    protected $path;

    /**
     * The request options.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Make the client with the given config.
     *
     * @param \Vimiso\Http\Config $config
     * @return void
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->client = $this->makeClient(
            $this->config->getBaseUri(),
            $this->config->getTimeout()
        );

        $this->mergeHeaders($this->config->getPackageHeaders());
    }

    /**
     * Set the request mode.
     *
     * @param string $mode
     * @return $this
     */
    public function setMode(string $mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Set the request method.
     *
     * @param string $method
     * @return $this
     */
    public function setMethod(string $method)
    {
        $this->method = strtoupper($method);

        return $this;
    }

    /**
     * Set the request headers.
     *
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Set the request parameters.
     *
     * @param array $params
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Set the request path.
     *
     * @param string $path
     * @return $this
     */
    public function setPath(string $path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Merge in the request headers.
     *
     * @param array $headers
     * @return $this
     */
    public function mergeHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    /**
     * Merge in the request parameters.
     *
     * @param array $params
     * @return $this
     */
    public function mergeParams(array $params)
    {
        $this->params = array_merge($this->params, $params);

        return $this;
    }

    /**
     * Merge in the request options.
     *
     * @param array $options
     * @return $this
     */
    public function mergeOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * Get the request mode.
     *
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * Get the request method.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get the request parameters.
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Get the request path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path ?: '/';
    }

    /**
     * Get the request options.
     *
     * @return array
     */
    public function getOptions(): array
    {
        $options = [
            $this->mode => $this->params,
            'headers' => $this->headers,
        ];

        return array_merge_recursive($options, $this->options);
    }

    /**
     * Make the request.
     *
     * @return array
     * @throws \Vimiso\Http\Exceptions\RequestException|\Throwable
     */
    public function make(): array
    {
        try {
            $response = $this->client->request(
                $this->getMethod(),
                $this->getPath(),
                $this->getOptions()
            );

            return $this->buildResponse($response->getBody()->getContents());
        } catch (GuzzleException $e) {
            [$statusCode, $response] = $this->unpackException($e);

            throw new RequestException($e->getMessage(), $statusCode, $response, $e);
        } catch (Throwable $e) {
            throw $e;
        }
    }

    /**
     * Make the client given the arguments.
     *
     * @param string $baseUri
     * @param int $timeout
     * @return \GuzzleHttp\Client
     */
    protected function makeClient(string $baseUri, int $timeout)
    {
        return new Client([
            'base_uri' => $baseUri,
            'timeout' => $timeout,
        ]);
    }

    /**
     * Build the response into an array.
     *
     * @param mixed $contents
     * @return array
     */
    protected function buildResponse($contents): array
    {
        return json_decode($contents, true) ?: ['body' => $contents];
    }

    /**
     * Unpack the exception's status code and response. Fallback to default values.
     *
     * @param \Throwable $e
     * @param int $statusCode
     * @param array $response
     * @return array
     */
    protected function unpackException(Throwable $e, $statusCode = 0, array $response = [])
    {
        if ($e instanceof GuzzleException && $e->hasResponse()) {
            $statusCode = $e->getResponse()->getStatusCode();
            $response = $this->buildResponse($e->getResponse()->getBody()->getContents());
        }

        return [$statusCode, $response];
    }
}
