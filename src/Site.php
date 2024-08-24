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

            $html = $page->getHtml();

            $this->createFile($path, (string) $html);
        }

        return $this;
    }

    /**
     * @param string $source
     * @param string $path
     *
     * @return self
     */
    public function copyDir($source, $path)
    {
        /** @var string */
        $source = realpath($source);

        $this->emptyDir((string) $path);

        /** @var string[] */
        $files = glob("$source/**/**.**");

        foreach ($files as $file)
        {
            /** @var string */
            $real = realpath($file);

            $name = dirname($real);

            $base = basename($real);

            /** @var string */
            $newpath = str_replace($source, $path, $name);

            if (! file_exists($newpath))
            {
                mkdir($newpath, 0777, true);
            }

            copy($file, "$newpath/$base");
        }

        return $this;
    }

    /**
     * @param string $path
     *
     * @return self
     */
    public function emptyDir($path)
    {
        $directory = new \RecursiveDirectoryIterator($path, 4096);

        $iterator = new \RecursiveIteratorIterator($directory, 2);

        /** @var \SplFileInfo $file */
        foreach ($iterator as $file)
        {
            $path = $file->getRealPath();

            if (strpos($path, '.git') !== false)
            {
                continue;
            }

            if ($file->isDir())
            {
                rmdir($path);
            }
            else
            {
                unlink($path);
            }
        }

        return $this;
    }

    /**
     * @param string $path
     * @param string $html
     *
     * @return void
     */
    protected function createFile($path, $html)
    {
        /** @var string */
        $path = str_replace('/index', '', $path);

        if (! file_exists($path))
        {
            mkdir($path, 0700, true);
        }

        $file = $path . '/index.html';

        file_put_contents($file, $html);
    }
}
