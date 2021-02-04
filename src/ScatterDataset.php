<?php
/**
 * Copyright 2021 Practically.io All rights reserved
 *
 * Use of this source is governed by a BSD-style
 * licence that can be found in the LICENCE file or at
 * https://www.practically.io/copyright/
 *
 * @package practically/chartjs
 * @since   1.0.2
 */
namespace practically\chartjs;

use yii\helpers\ArrayHelper;

/**
 * The data set for rendering a scatter chart.
 *
 * This will be looking for an x and y in your query instead on of the one
 * field in the main dataset
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
    public function prepareSet($set, $index)
    {
        $label = ArrayHelper::getValue($set, $this->labelAttribute);
        $this->data[$label] = [
            'x' => ArrayHelper::getValue($set, $this->dataAttributeX),
            'y' => ArrayHelper::getValue($set, $this->dataAttributeY),
        ];
    }
}
