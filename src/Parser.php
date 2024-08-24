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

        // Add timestamp if filename format is valid ---
        if ($file)
        {
            $data = (array) $page->getData();

            $timestamp = $this->getTimestamp($file);

            $data['created_at'] = $timestamp;

            $page = $page->setData((array) $data);
        }
        // ---------------------------------------------

        $page = $this->mergeMatter($page);

        $layout = $page->getLayout();

        $page = $this->parseHtml($page);

        if (! $this->render || ! $layout)
        {
            return $page;
        }

        $data = (array) $page->getData();

        $data = $this->insertHelpers($page, $data);

        if ($name = $layout->getName())
        {
            $html = $this->render->render($name, $data);

            $page = $page->setHtml($html);
        }

        return $this->useFilters($page);
    }

    /**
     * @param \Rougin\Staticka\Render\RenderInterface $render
     *
     * @return self
     */
    public function setRender(RenderInterface $render)
    {
        $this->render = $render;

        return $this;
    }

    /**
     * @param string $file
     *
     * @return integer|null
     */
    protected function getTimestamp($file)
    {
        $filename = basename($file);

        $timestamp = substr($filename, 0, 14);

        $valid = ((string) (int) $timestamp === $timestamp)
            && ($timestamp <= PHP_INT_MAX)
            && ($timestamp >= ~PHP_INT_MAX);

        /** @var integer|null */
        return $valid ? strtotime($timestamp) : null;
    }

    /**
     * @param \Rougin\Staticka\Page $page
     *
     * @return \Rougin\Staticka\Page
     */
    protected function mergeMatter(Page $page)
    {
        $data = $page->getData();

        $parsed = Matter::parse($page->getBody());

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

        if (array_key_exists('plate', $data))
        {
            $layout = $page->getLayout();

            /** @var string */
            $name = $data['plate'];

            if ($layout)
            {
                $layout->setName($name);

                $page->setLayout($layout);
            }

            if (class_exists($name))
            {
                /** @var \Rougin\Staticka\Layout */
                $layout = new $name;

                $page->setLayout($layout);
            }
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

        /** @var array<string, string> */
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

        if (! $layout)
        {
            return $data;
        }

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
            $html = $filter->filter((string) $html);
        }

        return $page->setHtml($html);
    }
}
