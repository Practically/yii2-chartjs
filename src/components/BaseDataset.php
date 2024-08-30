<?php

declare(strict_types=1);

namespace practically\chartjs\components;

use yii\base\Component;
use yii\db\ActiveQuery;
use yii\helpers\Json;

/**
 * The base dataset class for Chart.js.
 * Converts Yii2 queries into a json dataset compatible with Chart.js
 * 
 * Use of this source is governed by a BSD-style
 * licence that can be found in the LICENCE file or at
 * https://www.practically.io/copyright/
 *
 * @copyright 2024 Practically.io All rights reserved
 * @package practically/chartjs
 * @since 2.0.0
 */
abstract class BaseDataset extends Component
{

    /**
     * The sql query to execute to get the data
     *
     * @var \yii\db\Query|\yii\db\ActiveQuery|\yii\db\Command|null
     */
    public $query = null;

    /**
     * Default data to add to the dataset in the event that the query results
     * in an empty dataset
     *
     * @var array
     */
    public array $defaultData = [];

    /**
     * The main data array. This can be passed in directly or generated
     * by the `query` provided
     *
     * @var array
     */
    public array $data = [];

    /**
     * The label attribute in the query that will be used for the X axis
     * on the chart. `ArrayHelper` will be used to get the attribute
     * so '.' separated strings can be used i.e. `model.relation`
     *
     * @var string
     */
    public string $labelAttribute = 'label';

    /**
     * Border width to be encoded with the dataset
     *
     * @var int
     */
    public int $borderWidth = 1;

    /**
     * Options to add to the dataset. This will be json encoded.
     *
     * You can find a list of dataset options for different chart types
     * in the Chart.js docs under "Dataset Properties"
     *
     * @see https://www.chartjs.org/docs/latest/charts/
     *
     * @var array
     */
    public array $clientOptions = [];

    /**
     * Background colors to be used in the chart
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
     * Border colors to be used in the chart
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
    public string $label = '';

    /**
     * @var string|null
     */
    public ?string $xAxisID = null;

    /**
     * @var string|null
     */
    public ?string $yAxisID = null;

    /**
     * @var string|null
     */
    public ?string $stack = null;

    /**
     * @var int
     */
    public int $lineTension = 0;

    /**
     * Populates `$this->data` by manipulating a query row into the correct format
     * for the dataset
     *
     * For an example:
     * @see https://github.com/Practically/yii2-chartjs/blob/master/src/Dataset.php
     *
     * @param mixed $set The result from your query. Can be an array or a model if the query is an `ActiveQuery`
     * @param integer $index The index of the row from the query
     * @return void
     */
    abstract public function prepareSet($set, $index): void;

    /**
     * Executes `$query` and loads the results into the Chart.js dataset.
     * If the query is null, the method returns early.
     *
     * @return void
     */
    public function prepareDataset(): void
    {
        if ($this->query === null) {
            return;
        }

        $query = $this->query;
        $data = ($query instanceof ActiveQuery) ? $query->all() : $query->queryAll();

        $this->data = $this->defaultData;
        foreach ($data as $index => $set) {
            $this->prepareSet($set, $index);
        }
    }

    /**
     * Returns the count of data points in the dataset.
     *
     * @return int The count of data points.
     */
    public function getDataCount(): int
    {
        return count($this->data);
    }

    /**
     * Retrieves the dataset for the chart.
     *
     * This method prepares the dataset by merging the client options with the data values.
     * It also adds bar colors and sets other properties such as border width, fill, label, xAxisID, yAxisID, and stack.
     *
     * @return array The dataset for the chart.
     */
    public function getDataset(): array
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

        if ($this->xAxisID !== null) {
            $dataset['xAxisID'] = $this->xAxisID;
        }

        if ($this->yAxisID !== null) {
            $dataset['yAxisID'] = $this->yAxisID;
        }

        if ($this->stack !== null) {
            $dataset['stack'] = $this->stack;
        }

        $dataset['lineTension'] = $this->lineTension;

        return $dataset;
    }

    /**
     * Gets the labels for the chart from `$this->data`
     *
     * @return array
     */
    public function getLabels(): array
    {
        return array_keys($this->data);
    }

    /**
     * Returns the JSON representation of the dataset.
     *
     * @return string The JSON representation of the dataset.
     */
    public function getJsonDataset(): string
    {
        return Json::encode($this->getDataset());
    }

    /**
     * Returns the JSON-encoded labels for the dataset.
     *
     * @return string The JSON-encoded labels.
     */
    public function getJsonLabels():string
    {
        return Json::encode($this->getLabels());
    }

    /**
     * Adds bar colors to the dataset.
     *
     * @param array $dataset The dataset to add bar colors to.
     * @param string $attribute The attribute containing the bar colors. Can be an array or a string.
     * @param string $label The label for the bar colors.
     * @return array The updated dataset with added bar colors.
     */
    public function addBarColors(array $dataset, string $attribute, string $label): array
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
