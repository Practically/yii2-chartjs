<?php
/**
 * Use of this source is governed by a BSD-style
 * licence that can be found in the LICENCE file or at
 * https://www.practically.io/copyright/
 * 
 * @copyright 2024 Practically.io. All rights reserved
 * @package practically/chartjs
 * @since 2.0.0
 * 
 * @var \app\components\View $this
 * @var array $dataset
 * @var string $id
 * @var int $aspectRatio 
 * @var string $dataUnit
 */
declare(strict_types=1);

use practically\chartjs\widgets\Chart;
use yii\web\JsExpression;

$id = $id ?? null;
$aspectRatio = $aspectRatio ?? 2;
$dataUnit = $dataUnit ?? '';

$clientOptions = [
    'title' => [
        'display' => false,
    ],
    'aspectRatio' => $aspectRatio,
    'scales' => [
        'yAxes' => [
            [
                'ticks' => [
                    'suggestedMin' => 0,
                    'callback' => new JsExpression('function(value, index, values) {
                        return \'' . $dataUnit . '\'+value;
                    }')
                ]
            ]
        ]
    ],
    'tooltips' => [
        'callbacks' => [
            'label' => new JsExpression('function(tooltipItem, chart) {
                var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || \'\';
                return datasetLabel + \' ' . $dataUnit . '\'+tooltipItem.yLabel;
            }')
        ]
    ],
];

if (isset($incLegend) && $incLegend === false) {
    $clientOptions['legend'] = ['display' => false];
}

echo Chart::widget([
    'id' => $id,
    'datasets' => $dataset,
    'type' => Chart::TYPE_LINE,
    'clientOptions' => $clientOptions,
]);
