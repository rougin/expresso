<?php

use Rougin\Slytherin\Integration\Configuration;
use Staticka\Expresso\Express;

$root = dirname(dirname(__DIR__));

require $root . '/vendor/autoload.php';

/** @var string */
$appPath = realpath($root . '/app');

$app = new Express;

$config = new Configuration;
$config->load($appPath . '/config');

/** @var string */
$appUrl = $config->get('app.app_url');
$app->setAppUrl($appUrl);

/** @var string */
$siteUrl = $config->get('app.site_url');
$app->setSiteUrl($siteUrl);

$app->setConfigPath($appPath . '/config');

$app->setPagesPath($appPath . '/pages');

$app->run();
