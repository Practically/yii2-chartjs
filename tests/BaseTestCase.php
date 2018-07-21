<?php

namespace practically\chartjs\tests;

use Yii;
use yii\web\Application;

/**
 * The base test case for all common test code
 *
 * @package   practically/chartjs
 * @author    Ade Attwood <ade@practically.io>
 * @copyright 2018 Practically.io
 * @since     1.0.0
 */
class BaseTestCase extends \PHPUnit\Framework\TestCase
{

    /**
     * Creates the yii2 web application global instance available via `Yii`
     *
     * @return void
     */
    public function mockWebApp(): void
    {
        $tmpDir = dirname(__DIR__) . '/tmp';

        if (!is_dir($tmpDir)) {
            mkdir($tmpDir);
        }

        if (!is_dir($tmpDir . '/assets')) {
            mkdir($tmpDir . '/assets');
        }

        new Application([
            'id' => 'moc-app',
            'basePath' => dirname(__DIR__),
            'aliases' => [
                '@bower' => '@vendor/bower-asset',
                '@npm' => '@vendor/npm-asset',
            ],
            'components' => [
            'assetManager' => [
                    'bundles' => false,
                    'basePath' => $tmpDir
                ],
                'request' => [
                    'cookieValidationKey' => 'mock_app_csrf',
                    'scriptFile' => __DIR__ . '/index.php',
                    'scriptUrl' => '/index.php',
                ],
            ],
        ]);
    }
}
