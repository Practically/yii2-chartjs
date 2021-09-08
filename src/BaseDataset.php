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

use yii\db\ActiveQuery;
use yii\helpers\Json;

/**
 * The base dataset class for chart js converts yii2 queries into a json
 * dataset to be rendered by chart js
 */
abstract class BaseDataset extends \yii\base\Component
{

    /**
     * The sql query to executed to get the data
     *
     * @var null|\yii\db\Query|\yii\db\ActiveQuery|\yii\db\Command
     */
    public $query = null;

    /**
     * The label attribute in the query to be used in the X axils
     * of the chart. The Array helper will be used to get the attribute
     * so . separated strings can be used i.e. `model.relation`
     *
     * @var string
     */
    public $labelAttribute = 'label';

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
     * Options that will be json encoded and added to the dataset.
     *
     * You can find a list op dataset options on the documentation page for the
     * chart you are creating under "Dataset Properties"
     *
     * @see https://www.chartjs.org/docs/latest/charts/
     *
     * @var array
     */
    public $clientOptions = [];

    /**
     * Array of background colors to be used in the chart
     *
     * @var array|string
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
     * @var array|string
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
     * Populates `$this->data` manipulating a query row into the correct format
     * for the dataset format use are using
     *
     * For an example of a basic example:
     *
     * @see https://github.com/Practically/yii2-chartjs/blob/master/src/Dataset.php
     *
     * @param mixed   $set   The result from your query. Can be an array or a model if the query is and `ActiveQuery`
     * @param integer $index The index of the row from the query
     *
     * @return void
     */
    abstract public function prepareSet($set, $index);

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
            $this->prepareSet($set, $index);
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

        $dataset = array_merge($this->clientOptions, [
            'data' => array_values($this->data),
        ]);

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
