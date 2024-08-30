<?php

declare(strict_types=1);

namespace practically\chartjs\widget;

use practically\chartjs\components\Dataset;
use practically\chartjs\components\ScatterDataset;
use Yii;
use yii\base\Widget as BaseWidget;
use yii\helpers\Json;
use yii\helpers\Html;

/**
 * The chart js widget for adding the dom elements and adding
 * all the needed javascript into the view
 * 
 * Use of this source is governed by a BSD-style
 * licence that can be found in the LICENCE file or at
 * https://www.practically.io/copyright/
 * 
 * @copyright 2024 Practically.io. All rights reserved
 * @package practically/chartjs
 * @since 2.0.0
 */
class Chart extends BaseWidget
{

    /**
     * Chart type definitions
     */
    const TYPE_PIE = 'pie';
    const TYPE_DOUGHNUT = 'doughnut';
    const TYPE_BAR = 'bar';
    const TYPE_LINE = 'line';
    const TYPE_SCATTER = 'scatter';

    /**
     * An array of datasets to be rendered into the chart.
     * Can be a dataset object or an array used to configure the dataset.
     *
     * @var array
     */
    public $datasets = [];

    /**
     * The type of chart you want to render
     *
     * @var string|null
     */
    public ?string $type = null;

    /**
     * Labels for the X axis of the chart.
     * They can be defined or populated from the first dataset provided
     *
     * @var string[]
     */
    public array $labels = [];

    /**
     * Html options to be rendered on the html element
     *
     * @var array
     */
    public array $options = [];

    /**
     * Options to be sent to the Chart.js js contractor
     *
     * @var array
     */
    public array $clientOptions = [];

    /**
     * An array of events to add to the js
     *
     * @var array
     */
    public array $jsEvents = [];

    /**
     * The variable to be given to the js chart. By default the widget id will
     * be used. This can be used for manipulating the chart in external js.
     *
     * @var string|null
     */
    public ?string $jsVar = null;

    /**
     * The internal dataset array. This is built of all the configured 
     * dataset objects
     *
     * @var array
     */
    protected array $_datasets = [];

    /**
     * Builds the dataset and sets all of the default options
     *
     * @return void
     */
    public function init(): void
    {
        foreach ($this->datasets as $dataset) {
            if (is_array($dataset) && !isset($dataset['class'])) {
                $dataset['class'] = $this->getDefaultDatasetClass();
            }

            if (is_array($dataset)) {
                $dataset = Yii::createObject($dataset);
            }

            $this->_datasets[] = $dataset->getDataset();
            if (count($this->labels) === 0) {
                $this->labels = $dataset->getLabels();
            }
        }

        if ($this->jsVar === null && $this->id !== null) {
            $this->jsVar = $this->id;
        }

        parent::init();
    }

    /**
     * Gets the default data set class for the type of chart you are rendering
     *
     * @return string
     */
    public function getDefaultDatasetClass(): string
    {
        if ($this->type === self::TYPE_SCATTER) {
            return ScatterDataset::class;
        }

        return Dataset::class;
    }

    /**
     * Renders the widget adding the js to the view and returning the html
     *
     * @return string
     */
    public function run(): string
    {
        $clientOptions = [
            'type' => $this->type,
            'options' => $this->clientOptions,
            'data' => [
                'datasets' => $this->_datasets,
                'labels' => $this->labels,
            ]
        ];

        $json = Json::encode($clientOptions);
        $js = "window.{$this->jsVar}_el = document.getElementById('{$this->id}');";
        $js .= "window.{$this->jsVar} = new Chart({$this->jsVar}_el, {$json});";

        foreach ($this->jsEvents as $eventName => $handler) {
            $js .= "window.{$this->jsVar}_el.{$eventName} = $handler;";
        }

        $this->getView()->registerJs($js);

        $this->options['id'] = $this->id;

        return Html::tag('canvas', '', $this->options);
    }
}
