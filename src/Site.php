<?php

namespace Rougin\Staticka;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Site
{
    /**
     * @var \Rougin\Staticka\Page[]
     */
    protected $pages = array();

    /**
     * @var \Rougin\Staticka\Parser
     */
    protected $parser;

    /**
     * @param \Rougin\Staticka\Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param \Rougin\Staticka\Page $page
     *
     * @return self
     */
    public function addPage(Page $page)
    {
        $this->pages[] = $page;

        return $this;
    }

    /**
     * @param string $output
     *
     * @return self
     */
    public function build($output)
    {
        foreach ($this->pages as $page)
        {
            $page = $this->parser->parsePage($page);

            $path = $output . '/' . $page->getLink();

            /** @var string */
            $path = str_replace('/index', '', $path);

            if (! file_exists($path))
            {
                mkdir($path, 0700, true);
            }

            $file = $path . '/index.html';

            file_put_contents($file, $page->getHtml());
        }

        return $this;
    }
}
