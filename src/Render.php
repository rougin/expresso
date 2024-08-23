<?php

namespace Rougin\Staticka;

use Rougin\Staticka\Render\RenderInterface;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Render implements RenderInterface
{
    /**
     * @var string[]
     */
    protected $paths = array();

    /**
     * @param string[] $paths
     */
    public function __construct($paths)
    {
        $this->paths = (array) $paths;
    }

    /**
     * @param string               $name
     * @param array<string, mixed> $data
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function render($name, $data = array())
    {
        /** @var string */
        $name = str_replace('.php', '', $name);

        // Find the file from the specified paths --------
        $file = $this->find($name . '.php');

        if (! $file)
        {
            $text = 'Template "' . $name . '" not found.';

            throw new \InvalidArgumentException($text);
        }
        // -----------------------------------------------

        return $this->extract($file, $data);
    }

    /**
     * @param string               $file
     * @param array<string, mixed> $data
     *
     * @return string
     * @throws \UnexpectedValueException
     */
    protected function extract($file, $data)
    {
        extract($data);

        ob_start();

        include $file;

        $data = ob_get_contents();

        ob_end_clean();

        if (! $data)
        {
            $text = 'Cannot get the contents from file';

            throw new \UnexpectedValueException($text);
        }

        return $data;
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    protected function find($name)
    {
        $result = null;

        foreach ($this->paths as $path)
        {
            $files = $this->getFiles($path);

            // Check if the name matched from the files ----
            foreach ($files as $file)
            {
                if (strtolower($file) === strtolower($name))
                {
                    $result = $path . '/' . $name;
                }
            }
            // ---------------------------------------------
        }

        return $result;
    }

    /**
     * @param string $path
     *
     * @return string[]
     */
    protected function getFiles($path)
    {
        /** @var string */
        $path = str_replace('\\', '/', $path) . '/';

        // Search files from the specified directory ---
        $dir = new \RecursiveDirectoryIterator($path);

        $item = new \RecursiveIteratorIterator($dir);

        $pattern = '/^.+\.php$/i';

        $regex = new \RegexIterator($item, $pattern, 1);

        $items = array_keys(iterator_to_array($regex));
        // ---------------------------------------------

        // Only return the basename of the file --------
        foreach ($items as $index => $item)
        {
            /** @var string */
            $item = str_replace('\\', '/', $item);

            /** @var string */
            $item = preg_replace('/^\d\//i', '', $item);

            /** @var string */
            $item = str_replace($path, '', $item);

            $items[$index] = (string) $item;
        }
        // ---------------------------------------------

        return (array) $items;
    }
}
