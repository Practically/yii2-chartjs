<?php

declare(strict_types=1);

namespace practically\chartjs\assets;

use Yii;
use yii\helpers\Url;
use yii\web\AssetBundle;
use yii\web\View;

/**
 * @copyright 2024 Practically.io. All rights reserved
 */
class ChartjsAsset extends AssetBundle
{

    /**
     * @var array
     */
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js',
    ];
    
    /**
     * @var array
     */
    public $depends = [
        'yii\web\YiiAsset',
    ];

}
