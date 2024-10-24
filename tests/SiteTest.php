<?php

namespace Rougin\Staticka;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class SiteTest extends Testcase
{
    /**
     * @var \Rougin\Staticka\Site
     */
    protected $site;

    /**
     * @return void
     */
    public function doSetUp()
    {
        $this->site = new Site;
    }

    /**
     * @return void
     */
    public function test_build_with_pages()
    {
        $expected = $this->getHtml('FromMdFile');

        $file = __DIR__ . '/Fixture/Plates/HelloWorld.md';

        $page = new Page($file);

        $this->site->addPage($page);

        $this->buildSite();

        $actual = $this->getActualHtml('index');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_build_with_front_matter()
    {
        $expected = $this->getHtml('FrontMatter');

        $file = __DIR__ . '/Fixture/Plates/FrontMatter.md';

        $page = new Page($file);

        $this->site->addPage($page);

        $this->buildSite();

        $actual = $this->getActualHtml('hello-world');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @depends test_build_with_front_matter
     *
     * @return void
     */
    public function test_copy_entire_build_path()
    {
        $path = __DIR__ . '/Fixture/Build';

        $dest = __DIR__ . '/Fixture/Copied';

        $this->site->copyDir($path, $dest);

        $exists = $dest . '/hello-world/index.html';

        $this->assertTrue(file_exists($exists));
    }

    /**
     * @return void
     */
    public function test_with_data()
    {
        $expected = $this->getHtml('WithSiteData');

        $file = __DIR__ . '/Fixture/Plates/WithSiteData.md';

        $page = new Page($file);

        $data = array('website' => 'https://roug.in');

        $this->site->setData($data);

        $this->site->addPage($page);

        $this->buildSite();

        $actual = $this->getActualHtml('index');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    protected function buildSite()
    {
        $path = __DIR__ . '/Fixture/Build';

        $this->site->emptyDir($path)->build($path);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getActualHtml($name)
    {
        $name = $name !== 'index' ? $name . '/index' : 'index';

        $file = __DIR__ . '/Fixture/Build/' . $name . '.html';

        /** @var string */
        $result = file_get_contents($file);

        return str_replace("\r\n", "\n", $result);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getHtml($name)
    {
        $file = __DIR__ . '/Fixture/Output/' . $name . '.html';

        /** @var string */
        $result = file_get_contents($file);

        return str_replace("\r\n", "\n", $result);
    }
}
