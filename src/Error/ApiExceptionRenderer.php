<?php

namespace RestApi\Error;

use Cake\Core\Configure;
use Cake\Error\ExceptionRenderer;
use Cake\Network\Response;
use Exception;
use RestApi\Controller\ApiErrorController;

/**
 * API Exception Renderer.
 *
 * Captures and handles all unhandled exceptions. Displays valid json response.
 */
class ApiExceptionRenderer extends ExceptionRenderer
{

    /**
     * Returns error handler controller.
     *
     * @return ApiErrorController
     */
    protected function _getController()
    {
        return new ApiErrorController();
    }

    /**
     * Prepare response.
     *
     * @param Exception $exception Exception
     * @param array     $options   Array of options
     *
     * @return Response
     */
    private function __prepareResponse($exception, $options = [])
    {
        $response = $this->_getController()->response;
        $code = $this->_code($exception);
        $response->statusCode($this->_code($exception));

        $body = [
            'status' => !empty($options['responseStatus']) ? $options['responseStatus'] : 'NOK',
            'result' => [
                'error' => ($code < 500) ? 'Not Found' : 'An Internal Error Has Occurred.',
            ],
        ];

        Configure::write('exceptionMessage', $exception->getMessage());

        $response->type('json');
        $response->body(json_encode($body));

        ApiRequestLogger::log($this->_getController()->request, $response);

        return $response;
    }
}
