<?php

declare(strict_types=1);

namespace practically\chartjs\components;

use yii\helpers\ArrayHelper;

/**
 * The dataset class for Chart.js.
 * Converts Yii2 queries into a json dataset compatible with Chart.js
 *
 * Use of this source is governed by a BSD-style
 * licence that can be found in the LICENCE file or at
 * https://www.practically.io/copyright/
 *
 * @copyright 2024 Practically.io All rights reserved
 * @package practically/chartjs
 * @since 1.3.0
 */
class Dataset extends BaseDataset
{
    /**
     * The attribute in the query to get the data from
     *
     * @var string
     */
    public $dataAttribute = 'data';

    /**
     * @inheritdoc
     */
    public function prepareSet($set, $index): void
    {
        $this->data[ArrayHelper::getValue($set, $this->labelAttribute)] = ArrayHelper::getValue($set, $this->dataAttribute);
    }
}
