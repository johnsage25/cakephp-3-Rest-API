<?php

namespace RestApi\Controller;

use Cake\Event\Event;

/**
 * Api error controller
 *
 * This controller will sets configuration to render errors
 */
class ApiErrorController extends AppController
{

    /**
     * beforeRender callback.
     *
     * @param Event $event Event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        $this->httpStatusCode = $this->response->statusCode();

        $messageArr = $this->response->httpCodes($this->httpStatusCode);

        $this->apiResponse['message'] = !empty($messageArr[$this->httpStatusCode]) ? $messageArr[$this->httpStatusCode] : 'Unknown error!';

        parent::beforeRender($event);

        $this->viewBuilder()->className('RestApi.ApiError');
    }
}
