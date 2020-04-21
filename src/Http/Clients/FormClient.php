<?php

namespace Vimiso\Http\Clients;

use Vimiso\Http\Config;
use Vimiso\Http\Request;

class FormClient extends Request
{
    /**
     * The request mode.
     *
     * `form_params` is Guzzle's `application/x-www-form-urlencoded`.
     *
     * @link http://docs.guzzlephp.org/en/stable/request-options.html#form-params
     * @var string
     */
    protected $mode = 'form_params';
}
