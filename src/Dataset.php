<?php

namespace practically\chartjs;

use yii\helpers\ArrayHelper;

/**
 * The dataset class for chart js converts yii2 queries into a json
 * dataset to be rendered by chart js
 *
 * @package   practically/chartjs
 * @author    Ade Attwood <ade@practically.io>
 * @author    Neil Davies <neil@practically.io>
 * @copyright 2018 Practically.io
 * @since     1.0.0
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
