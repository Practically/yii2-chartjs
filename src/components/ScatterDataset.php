<?php

declare(strict_types=1);

namespace practically\chartjs\components;

use yii\helpers\ArrayHelper;

/**
 * The data set for rendering a scatter chart.
 *
 * This will be looking for `x` and `y` attributes in your query instead
 * of the one field in the main dataset
 *
 * Use of this source is governed by a BSD-style
 * licence that can be found in the LICENCE file or at
 * https://www.practically.io/copyright/
 *
 * @copyright 2024 Practically.io All rights reserved
 * @package practically/chartjs
 * @since 1.3.0
 */
class ScatterDataset extends BaseDataset
{

    /**
     * The attribute in the query to populate the x axis on the chart
     *
     * @var string
     */
    public $dataAttributeX = 'x';

    /**
     * The attribute in the query to populate the y axis on the chart
     *
     * @var string
     */
    public $dataAttributeY = 'y';

    /**
     * @inheritdoc
     */
    public function prepareSet($set, $index): void
    {
        $label = ArrayHelper::getValue($set, $this->labelAttribute);
        $this->data[$label] = [
            'x' => ArrayHelper::getValue($set, $this->dataAttributeX),
            'y' => ArrayHelper::getValue($set, $this->dataAttributeY),
        ];
    }
}
