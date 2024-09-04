<?php

declare(strict_types=1);

namespace practically\chartjs\assets;

use yii\web\AssetBundle;

/**
 * ChartjsAsset represents a bundle of the Chart.js library.
 *
 * Use of this source is governed by a BSD-style
 * licence that can be found in the LICENCE file or at
 * https://www.practically.io/copyright/
 *
 * @copyright 2024 Practically.io All rights reserved
 * @package practically/chartjs
 * @since 1.3.0
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
