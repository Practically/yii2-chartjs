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
namespace practically\chartjs\tests;

use practically\chartjs\Dataset;

/**
 * Test for the chart js dataset class
 */
class DatasetTest extends BaseTestCase
{

    /**
     * The global dataset to be used in all tests.
     *
     * @var Dataset
     */
    protected $dataset;

    /**
     * Creates the global dataset for before each test
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->dataset = new Dataset([
            'data' => [
                'Label 1' => 10,
                'Label 2' => 20,
                'Label 3' => 30,
                'Label 4' => 40,
                'Label 5' => 50,
                'Label 6' => 60,
                'Label 7' => 70,
                'Label 8' => 80,
                'Label 9' => 90,
            ],
            'clientOptions' => [
                'pointRadius' => 6,
            ],
        ]);

        parent::setup();
    }

    /**
     * Test that the dataset get the labels from the array keys of the
     * provided data
     *
     * @return void
     */
    public function testGettingDataLabels(): void
    {
        $dataset = $this->dataset;
        $this->assertTrue(in_array('Label 1', $dataset->getLabels()));
        $this->assertTrue(in_array('Label 2', $dataset->getLabels()));
    }

    /**
     * Test the data count method calculates the correct count
     *
     * @return void
     */
    public function testGettingDataCount(): void
    {
        $dataset = $this->dataset;
        $this->assertEquals(9, $dataset->getDataCount());
    }

    /**
     * Test the output dataset has the correct data
     *
     * @return void
     */
    public function testDataSetAttributes(): void
    {
        $dataset = $this->dataset->getDataset();
        $this->assertArrayHasKey('data', $dataset);
        $this->assertArrayHasKey('backgroundColor', $dataset);
        $this->assertArrayHasKey('borderColor', $dataset);
    }

    /**
     * Test there are enough colors when more labels then colors are provided

     * @return void
     */
    public function testColorCount(): void
    {
        $dataset = $this->dataset->getDataset();
        $this->assertGreaterThanOrEqual(
            count($dataset['data']),
            count($dataset['backgroundColor'])
        );
    }

    /**
     * Test that you can use string as colors for line charts
     *
     * @return void
     */
    public function testColorsAsStrings(): void
    {
        $this->dataset->backgroundColors = 'red';
        $this->dataset->borderColors = 'blue';
        $dataset = $this->dataset->getDataset();

        $this->assertEquals('red', $dataset['backgroundColor']);
        $this->assertEquals('blue', $dataset['borderColor']);
    }

    /**
     * Tests you can set the fill property of the dataset
     *
     * @return void
     */
    public function testDatasetFill(): void
    {
        $this->assertArrayNotHasKey(
            'fill',
            $this->dataset->getDataset()
        );

        $this->dataset->fill = false;
        $this->assertFalse($this->dataset->getDataset()['fill']);
    }

    /**
     * Test you can add a label to the dataset
     *
     * @return void
     */
    public function testAddingLabelsToDatasets(): void
    {
        $this->assertArrayNotHasKey(
            'label',
            $this->dataset->getDataset()
        );

        $this->dataset->label = 'My New Label';
        $this->assertEquals(
            'My New Label',
            $this->dataset->getDataset()['label']
        );
    }

    /**
     * Test the client options get merged into the data
     *
     * @return void
     */
    public function testClientOptions(): void
    {
        $this->assertEquals(
            6,
            $this->dataset->getDataset()['pointRadius']
        );
    }
}
