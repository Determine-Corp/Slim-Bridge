<?php

namespace DI\Bridge\Slim\Test;

use DI\Bridge\Slim\Quickstart;
use DI\Bridge\Slim\Test\Mock\RequestFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class MiddlewareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function invokes_closure_middleware()
    {
        $app = Quickstart::createApplication();

        $app->addMiddleware(function (ServerRequestInterface $request, ResponseInterface $response, callable $next) {
            $response->getBody()->write('Hello ' . $request->getQueryParams()['foo']);
            return $response;
        });

        $app->get('/', function () {});
        $request = RequestFactory::create('/', 'foo=matt');
        $response = $app->callMiddlewareStack($request, new Response);
        $this->assertEquals('Hello matt', $response->getBody()->__toString());
    }
}
