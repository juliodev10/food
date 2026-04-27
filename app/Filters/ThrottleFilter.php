<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ThrottleFilter implements FilterInterface
{
    /**
     * This is a demo implementation of using the Throttler class
     * to implement rate limiting for your application.
     *
     * @param list<string>|null $arguments
     *
     * @return ResponseInterface|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $path = trim((string) $request->getUri()->getPath(), '/');

        if (
            preg_match('#^admin/pedidos/atualizar/[^/]+$#', $path) === 1
            || preg_match('#^checkout/atualizarcanal/[^/]+$#', $path) === 1
        ) {
            return;
        }

        $throttler = service('throttler');

        // Restrict an IP address to no more than 1 request
        // per second across the entire site.
        if ($throttler->check(md5($request->getIPAddress()), 60, MINUTE) === false) {
            return service('response')->setStatusCode(429);
        }
    }

    /**
     * We don't have anything to do here.
     *
     * @param list<string>|null $arguments
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ...
    }
}
