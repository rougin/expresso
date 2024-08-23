<?php

namespace Rougin\Staticka;

use Rougin\Staticka\Filter\FilterInterface;
use Rougin\Staticka\Helper\HelperInterface;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Layout
{
    /**
     * @var \Rougin\Staticka\Filter\FilterInterface[]
     */
    protected $filters = array();

    /**
     * @var \Rougin\Staticka\Helper\HelperInterface[]
     */
    protected $helpers = array();

    /**
     * @var string|null
     */
    protected $name = null;

    /**
     * @return \Rougin\Staticka\Filter\FilterInterface[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @return \Rougin\Staticka\Helper\HelperInterface[]
     */
    public function getHelpers()
    {
        return $this->helpers;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \Rougin\Staticka\Filter\FilterInterface $filter
     *
     * @return self
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * @param \Rougin\Staticka\Helper\HelperInterface $helper
     *
     * @return self
     */
    public function addHelper(HelperInterface $helper)
    {
        $this->helpers[] = $helper;

        return $this;
    }

    /**
     * @param string|null $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
