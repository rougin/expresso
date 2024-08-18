<?php

namespace Rougin\Staticka;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Page
{
    const TYPE_BODY = 0;

    const TYPE_FILE = 1;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var array<string, mixed>
     */
    protected $data = array();

    /**
     * @var string|null
     */
    protected $file = null;

    /**
     * @var string|null
     */
    protected $html = null;

    /**
     * @var string|null
     */
    protected $link = null;

    /**
     * @var string|null
     */
    protected $name = null;

    /**
     * @param string|null $data
     * @param integer     $type
     */
    public function __construct($data = null, $type = self::TYPE_FILE)
    {
        if ($type === self::TYPE_FILE)
        {
            $this->file = $data;
        }

        if ($type === self::TYPE_BODY)
        {
            $this->body = $data;
        }
    }

    /**
     * @return string|null
     */
    public function __toString()
    {
        return $this->html;
    }

    /**
     * @var string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @var array<string, mixed>
     */
    public function getData()
    {
        $data = $this->data;

        $data['name'] = $this->name;

        return (array) $data;
    }

    /**
     * @var string|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @var string|null
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @var string|null
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @var string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $body
     *
     * @return self
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param string $file
     *
     * @return self
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @param string|null $html
     *
     * @return self
     */
    public function setHtml($html = null)
    {
        $this->html = $html;

        return $this;
    }

    /**
     * @param string $link
     *
     * @return self
     */
    public function setLink($link)
    {
        $this->link = $link;

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
