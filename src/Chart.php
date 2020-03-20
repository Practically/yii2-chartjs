<?php

namespace practically\chartjs;

use Yii;
use yii\helpers\Json;
use yii\helpers\Html;

/**
 * The chart js widget for adding the dom elements and adding
 * all the needed javascript into the view
 *
 * @package   practically/chartjs
 * @author    Ade Attwood <ade@practically.io>
 * @copyright 2018 Practically.io
 * @since     1.0.0
 */
class Chart extends \yii\base\Widget
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
     * An array of datasets to be rendered into the chart
     * can be an dataset object or a array used to configure the dataset.
     *
     * @var array
     */
    public $datasets = [];

    /**
     * The type of chart you want to render
     *
     * @var string
     */
    public $type = null;

    /**
     * Labels for the X axis of the chart
     * they can be defined ot populated from the first dataset provided
     *
     * @var string[]
     */
    public $labels = null;

    /**
     * Html options to be rendered on the html element
     *
     * @var array
     */
    public $options = [];

    /**
     * Options to be sent to the chart js javascript contractor
     *
     * @var array
     */
    public $clientOptions = [];

    /**
     * An array of events to add to the javascript
     *
     * @var array
     */
    public $jsEvents = [];

    /**
     * The variable to be given the js chart be default the widget id will
     * be used. This can be used for manipulating the chart in external js.
     *
     * @var string
     */
    public $jsVar;

    /**
     * The internal dataset array.
     *
     * This is build of all the configured dataset objects
     *
     * @var array
     */
    protected $_datasets = [];

    /**
     * Initializes the widget building the dataset and addling all of the
     * default options
     *
     * @return void
     */
    public function init()
    {
        foreach ($this->datasets as $dataset) {
            if (is_array($dataset) && !isset($dataset['class'])) {
                $dataset['class'] = $this->getDefaultDatasetClass();
            }

            if (is_array($dataset)) {
                $dataset = \Yii::createObject($dataset);
            }

            $this->_datasets[] = $dataset->getDataset();
            if ($this->labels === null) {
                $this->labels = $dataset->getLabels();
            }
        }

        if ($this->jsVar === null) {
            $this->jsVar = $this->id;
        }

        return parent::init();
    }

    /**
     * Gets the default data set class for the type of chart you are rendering
     *
     * @return string
     */
    public function getDefaultDatasetClass()
    {
        if ($this->type === self::TYPE_SCATTER) {
            return 'practically\chartjs\ScatterDataset';
        }

        return 'practically\chartjs\Dataset';
    }

    /**
     * Renders the widget adding the js to the view and returning the
     * html need
     *
     * @return string
     */
    public function run()
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
        $js  = "window.{$this->jsVar}_el = document.getElementById('{$this->id}');";
        $js .= "window.{$this->jsVar} = new Chart({$this->jsVar}_el, {$json});";

        foreach ($this->jsEvents as $eventName => $handler) {
            $js .= "window.{$this->jsVar}_el.{$eventName} = $handler;";
        }

        $this->getView()->registerJs($js);

        $this->options['id'] = $this->id;

        return Html::tag('canvas', null, $this->options);
    }
}
