<?php

namespace App\Http\Middleware;

use Fideloper\Proxy\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{ //https://laravel.com/docs/master/requests#configuring-trusted-proxies
    /**
     * The trusted proxies for this application.
     *
     * @var array|string|null
     */
//    protected $proxies = '*';
    protected $proxies = [
        '192.168.1.1',
        '192.168.1.2',
    ];

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
