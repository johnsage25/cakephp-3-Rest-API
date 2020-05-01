<?php

namespace RestApi\Middleware;

use Cake\Core\App;
use Cake\Error\Middleware\ErrorHandlerMiddleware;

class RestApiMiddleware extends ErrorHandlerMiddleware
{

    /**
     * Override ErrorHandlerMiddleware and add custom exception renderer
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Message\ResponseInterface $response The response.
     * @param callable $next Callback to invoke the next middleware.
     * @return \Psr\Http\Message\ResponseInterface A response
     */
    public function __invoke($request, $response, $next)
    {
        try {
            $params = (array)$request->getAttribute('params', []);
            if (isset($params['controller'])) {
                $className = App::className($params['controller'], 'Controller', 'Controller');
                $controller = ($className) ? new $className() : null;
                if ($controller && 'RestApi\Controller\ApiController' === get_parent_class($controller)) {
                    unset($controller);
                    $this->renderer = 'RestApi\Error\ApiExceptionRenderer';
                }
            }

            return $next($request, $response);
        } catch (\Exception $e) {
            return $this->handleException($e, $request, $response);
        }
    }
}
