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
        $body = $page->getBody();

        $data = $page->getData();

        $file = $page->getFile();

        if ($file && $this->render)
        {
            $body = $this->render->render($file, $data);
        }

        // Merge Front Matter to the existing data ---
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
        // -------------------------------------------

        /** @var string */
        $body = $parsed[1];

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
}
