<?php

namespace RestApi\View;

use Cake\Core\Exception\Exception;
use Cake\View\View;

/**
 * Api Error View
 *
 * Default view class for error
 */
class ApiErrorView extends View
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
     * Renders custom api error view
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

        $this->layout = 'RestApi.error';

        $this->Blocks->set('content', $this->renderLayout('', $this->layout));

        $this->hasRendered = true;

        return $this->Blocks->get('content');
    }
}
