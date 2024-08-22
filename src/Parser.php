<?php

namespace Rougin\Staticka;

use Rougin\Staticka\Render\RenderInterface;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Parser extends \Parsedown
{
    /**
     * @var \Rougin\Staticka\Render\RenderInterface|null
     */
    protected $render = null;

    /**
     * @param \Rougin\Staticka\Render\RenderInterface|null $render
     */
    public function __construct(RenderInterface $render = null)
    {
        $this->render = $render;
    }

    /**
     * @param \Rougin\Staticka\Page $page
     *
     * @return \Rougin\Staticka\Page
     */
    public function parsePage(Page $page)
    {
        $file = $page->getFile();

        if ($file)
        {
            /** @var string */
            $body = file_get_contents($file);

            $page->setBody($body);
        }

        $page = $this->mergeMatter($page);

        $layout = $page->getLayout();

        $page = $this->parseHtml($page);

        if (! $this->render || ! $layout)
        {
            return $page;
        }

        $data = (array) $page->getData();

        $data['page'] = $page->getHtml();

        if ($name = $layout->getName())
        {
            $data = $this->insertHelpers($page, $data);

            $name = $layout->getName();

            $html = $this->render->render($name, $data);

            $page = $page->setHtml($html);
        }

        return $this->useFilters($page);
    }

    /**
     * @param \Rougin\Staticka\Page $page
     *
     * @return \Rougin\Staticka\Page
     */
    protected function mergeMatter(Page $page)
    {
        $data = $page->getData();

        $body = $page->getBody();

        $parsed = Matter::parse($body);

        /** @var array<string, mixed> */
        $matter = $parsed[0];

        $data = array_merge($data, $matter);

        $page->setData($data);

        if (array_key_exists('link', $data))
        {
            /** @var string */
            $link = $data['link'];
            $page->setLink($link);
        }

        if (array_key_exists('name', $data))
        {
            /** @var string */
            $name = $data['name'];
            $page->setName($name);
        }

        if (array_key_exists('layout', $data))
        {
            $layout = new Layout;

            /** @var string */
            $name = $data['layout'];

            if (class_exists($name))
            {
                /** @var \Rougin\Staticka\Layout */
                $layout = new $name;
            }
            else
            {
                $layout->setName($name);
            }

            $page->setLayout($layout);
        }

        /** @var string */
        $body = $parsed[1];

        return $page->setBody($body);
    }

    /**
     * @param \Rougin\Staticka\Page $page
     *
     * @return \Rougin\Staticka\Page
     */
    protected function parseHtml(Page $page)
    {
        $body = $page->getBody();

        $data = $page->getData();

        // Converts placeholder in body, if any -----
        foreach ($data as $key => $value)
        {
            $key = '{' . strtoupper($key) . '}';

            $body = str_replace($key, $value, $body);
        }
        // ------------------------------------------

        $html = $this->parse($body);

        return $page->setHtml($html);
    }

    /**
     * @param \Rougin\Staticka\Page $page
     * @param array<string, mixed>  $data
     *
     * @return array<string, mixed>
     */
    protected function insertHelpers(Page $page, $data)
    {
        $layout = $page->getLayout();

        $helpers = $layout->getHelpers();

        foreach ($helpers as $helper)
        {
            $data[$helper->name()] = $helper;
        }

        return (array) $data;
    }

    /**
     * @param \Rougin\Staticka\Page $page
     *
     * @return \Rougin\Staticka\Page
     */
    protected function useFilters(Page $page)
    {
        $layout = $page->getLayout();

        if (! $layout)
        {
            return $page;
        }

        $filters = $layout->getFilters();

        $html = $page->getHtml();

        foreach ($filters as $filter)
        {
            $html = $filter->filter($html);
        }

        return $page->setHtml($html);
    }
}
