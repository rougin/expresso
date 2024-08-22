<?php

namespace Rougin\Staticka\Helper;

use Rougin\Staticka\Helper\HelperInterface;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ViewHelper implements HelperInterface
{
    /**
     * @var \Rougin\Staticka\Render
     */
    protected $render;

    /**
     * @param \Rougin\Staticka\Render $render
     */
    public function __construct($render)
    {
        $this->render = $render;
    }

    /**
     * Renders the partial template.
     *
     * @param string               $name
     * @param array<string, mixed> $data
     *
     * @return string
     */
    public function render($name, $data = array())
    {
        return $this->render->render($name, $data);
    }

    /**
     * Returns the name of the helper.
     *
     * @return string
     */
    public function name()
    {
        return 'view';
    }
}
