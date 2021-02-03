<?php
/**
 * Copyright 2021 Practically.io All rights reserved
 *
 * Use of this source is governed by a BSD-style
 * licence that can be found in the LICENCE file or at
 * https://www.practically.io/copyright/
 *
 * @package practically/chartjs
 * @since   1.0.0
 */
namespace practically\chartjs;

use yii\helpers\ArrayHelper;

/**
 * The dataset class for chart js converts yii2 queries into a json
 * dataset to be rendered by chart js
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
    public function prepareSet($set, $index)
    {
        $this->data[ArrayHelper::getValue($set, $this->labelAttribute)] = ArrayHelper::getValue($set, $this->dataAttribute);
    }
}
