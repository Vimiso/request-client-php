<?php

namespace Vimiso\Http\Clients;

use Vimiso\Http\Config;
use Vimiso\Http\Request;

class JsonClient extends Request
{
    /**
     * The request mode.
     *
     * `json` is Guzzle's JSON request option.
     *
     * @link http://docs.guzzlephp.org/en/stable/request-options.html#json
     * @var string
     */
    protected $mode = 'json';
}
