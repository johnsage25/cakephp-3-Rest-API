<?php

namespace RestApi\View;

use Cake\Core\Exception\Exception;
use Cake\View\View;

/**
 * Api View
 *
 * Default view class for rendering API response
 */
class ApiView extends View
{

    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->response->type('json');
    }

    /**
     * Renders api response
     *
     * @param string|null $view Name of view file to use
     * @param string|null $layout Layout to use.
     * @return string|null Rendered content or null if content already rendered and returned earlier.
     * @throws Exception If there is an error in the view.
     */
    public function render($view = null, $layout = null)
    {
        if ($this->hasRendered) {
            return null;
        }

        $this->layout = 'RestApi.response';

        $this->Blocks->set('content', $this->renderLayout('', $this->layout));

        $this->hasRendered = true;

        return $this->Blocks->get('content');
    }
}
