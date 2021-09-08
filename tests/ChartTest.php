<?php
/**
 * Copyright 2021 Practically.io All rights reserved
 *
 * Use of this source is governed by a BSD-style
 * licence that can be found in the LICENCE file or at
 * https://www.practically.io/copyright/
 */
namespace practically\chartjs\tests;

use Yii;
use yii\web\View;

use practically\chartjs\Dataset;
use practically\chartjs\Chart;

/**
 * Test for the chart js chart widget
 *
 * @package   practically/chartjs
 * @author    Ade Attwood <ade@practically.io>
 * @copyright 2018 Practically.io
 * @since     1.0.0
 */
class ChartTest extends BaseTestCase
{

    /**
     * Runs before each test settings up the yii2 web application
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->mockWebApp();

        parent::setUp();
    }

    /**
     * Tests the widget output is the widget is correct
     *
     * @return void
     */
    public function testChartDomOptions(): void
    {
        $chart = Chart::widget([
            'datasets' => [
                [
                    'data' => [
                        'Key 1' => 10,
                        'Key 2' => 20
                    ]
                ]
            ]
        ]);

        $this->assertEquals('<canvas id="w0"></canvas>', $chart);
    }

    /**
     * Test that the js has been added to the view
     *
     * @return void
     */
    public function testRegisterJsHasLabelsAndValues(): void
    {
        $chart = Chart::widget([
            'datasets' => [
                [
                    'data' => [
                        'Key 1' => 10,
                        'Key 2' => 20
                    ]
                ]
            ]
        ]);

        $view = Yii::$app->getView();
        assert($view instanceof View);
        $js = end($view->js[View::POS_READY]);

        $this->assertStringContainsString('"labels":["Key 1","Key 2"]', $js);
        $this->assertStringContainsString('"data":[10,20]', $js);
    }

    /**
     * Testes that you can change the js var to manipulate the cart
     * in external js.
     *
     * @return void
     */
    public function testChangingTheJsVar(): void
    {
        $chart = Chart::widget(
            [
            'id' => 'MyChartId',
            'jsVar' => 'MyChart',
            'datasets' => [
                [
                    'data' => [
                        'Key 1' => 10,
                        'Key 2' => 20
                    ]
                ]
            ]
            ]
        );
        $view = Yii::$app->getView();
        assert($view instanceof View);
        $js = end($view->js[View::POS_READY]);

        $this->assertStringContainsString('window.MyChart = new Chart(', $js);
    }

    /**
     * Test rendering a scatter chart
     *
     * @return void
     */
    public function testScatterChart(): void
    {
        $chart = Chart::widget([
            'type' => Chart::TYPE_SCATTER,
            'datasets' => [
                [
                    'data' => [
                        'Key 1' => ['x' => 10, 'y' => 10],
                        'Key 2' => ['x' => 20, 'y' => 20],
                    ]
                ]
            ]
        ]);

        $view = Yii::$app->getView();
        assert($view instanceof View);
        $js = end($view->js[View::POS_READY]);

        $this->assertStringContainsString('"type":"scatter"', $js);
        $this->assertStringContainsString('"data":[{"x":10,"y":10},{"x":20,"y":20}]', $js);
    }
}
