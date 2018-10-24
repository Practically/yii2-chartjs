<?php

namespace practically\chartjs;

use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

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
class Dataset extends \yii\base\Component
{

    /**
     * The sql query to executed to get the data
     *
     * @var yii\db\Query|yii\db\ActiveQuery
     */
    public $query;

    /**
     * The label attribute in the query to be used in the X axils
     * of the chart. The Array helper will be used to get the attribute
     * so . separated strings can be used i.e. `model.relation`
     *
     * @var string
     */
    public $labelAttribute = 'label';

    /**
     * The attribute in the query to get the data from
     *
     * @var string
     */
    public $dataAttribute = 'data';

    /**
     * Default data to add the the dataset.
     *
     * If your query dose not return a group of data it will not be shown in
     * the chart. You can define it in the default data to add it into the
     * chart
     *
     * @var array
     */
    public $defaultData = [];

    /**
     * The main data array this can be passed in directly or generated
     * by the `query` provided
     *
     * @var array
     */
    public $data = [];

    /**
     * Border width to be encoded with the dataset
     *
     * @var integer
     */
    public $borderWidth = 1;

    /**
     * Array of background colors to be used in the chart
     *
     * @var array
     */
    public $backgroundColors = [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)'
    ];

    /**
     * Array of border colors to be used in the chart
     *
     * @var array
     */
    public $borderColors = [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
    ];

    /**
     * The fill for the dataset
     *
     * @see https://www.chartjs.org/docs/latest/charts/area.html#filling-modes
     *
     * @var mixed
     */
    public $fill = null;

    /**
     * Label for the dataset
     *
     * @var string
     */
    public $label = '';

    /**
     * Executes the query and populates the data with the result of the query
     *
     * @return void
     */
    public function prepareDataset()
    {
        if ($this->query === null) {
            return;
        }

        $query = $this->query;
        $data  = ($query instanceof ActiveQuery) ? $query->all() : $query->queryAll();

        $this->data = $this->defaultData;
        foreach ($data as $index => $set) {
            $this->data[ArrayHelper::getValue($set, $this->labelAttribute)] = ArrayHelper::getValue($set, $this->dataAttribute);
        }
    }

    /**
     * Get the count of data in this dataset
     *
     * @return integer
     */
    public function getDataCount()
    {
        return count($this->data);
    }

    /**
     * Gets prepared dataset adding all the needed attributes
     *
     * @return array
     */
    public function getDataset()
    {
        $this->prepareDataset();

        $dataset = [
            'data' => array_values($this->data),
        ];

        $dataset = $this->addBarColors($dataset, 'backgroundColors', 'backgroundColor');
        $dataset = $this->addBarColors($dataset, 'borderColors', 'borderColor');

        if ($this->borderWidth > 0) {
            $dataset['borderWidth'] = $this->borderWidth;
        }

        if ($this->fill !== null) {
            $dataset['fill'] = $this->fill;
        }

        if (strlen($this->label) > 0) {
            $dataset['label'] = $this->label;
        }

        return $dataset;
    }

    /**
     * Gets an array labels for the chart
     *
     * @return array
     */
    public function getLabels()
    {
        return array_keys($this->data);
    }

    /**
     * Encodes the dataset into json ready to be passed to the chart js options
     *
     * @return string
     */
    public function getJsonDataset()
    {
        return Json::encode($this->getDataset());
    }

    /**
     * Encodes the labels into json ready to be passed to the chart js options
     *
     * @return string
     */
    public function getJsonLabels()
    {
        return Json::encode($this->getLabels());
    }

    /**
     * Build the array of colors and adding them to the dataset
     * if there is more data that colors it will start repeat the colors
     *
     * @param array  $dataset   The dataset to manipulate
     * @param string $attribute The name of the color array to use
     * @param string $label     The key to give the color set
     *
     * @return array
     */
    public function addBarColors($dataset, $attribute, $label)
    {
        if (is_array($this->$attribute)) {
            $i = 0;
            while ($i < count($this->data)) {
                foreach ($this->$attribute as $x => $bg) {
                    $dataset[$label][$x+$i] = $bg;
                }

                $i += count($this->$attribute);
            }
        } elseif (is_string($this->$attribute)) {
            $dataset[$label] = $this->$attribute;
        }

        return $dataset;
    }
}
