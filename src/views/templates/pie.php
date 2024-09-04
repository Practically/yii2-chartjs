<?php
/**
 * Use of this source is governed by a BSD-style
 * licence that can be found in the LICENCE file or at
 * https://www.practically.io/copyright/
 *
 * @copyright 2024 Practically.io. All rights reserved
 * @package practically/chartjs
 * @since 1.3.0
 *
 * @var \yii\web\View $this
 * @var array $clientOptions
 * @var array $dataset
 * @var string $id
 */
declare(strict_types=1);

use practically\chartjs\widgets\Chart;

$id ??= 'pieChart';

echo Chart::widget([
    'id' => $id,
    'datasets' => $dataset,
    'type' => Chart::TYPE_PIE,
    'clientOptions' => $clientOptions,
]);
