<?php

namespace Rougin\Staticka;

use Rougin\Staticka\Render\RenderInterface;

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
     * @return string
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

        return $this->parse($body);
    }
}
