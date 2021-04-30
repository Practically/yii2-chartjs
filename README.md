# Yii2 Chart JS

Wrapper for chart js in Yii2. Easily turn Yii2 querys into beautiful charts.

## Installation

The preferred way is with composer.

```bash
composer require practically/yii2-chartjs
```

**Note:** This package does not handle the installation of chart js library. For
that you can visit the [chart js website](http://www.chartjs.org/docs/latest/getting-started/installation.html)

You can also install with the [asset packagist](https://asset-packagist.org/)

```bash
composer require bower-asset/chart-js
```

## Chart JS Versions

Please note that there are API changes in the `clientOptions` of the
config for chart js between versions 2 and 3. We have documented the
basic API for this library in the relevent sections of the docs. If
you are updating to v3 you can find more info in the [Chart JS 3.x
Migration
Guide](https://www.chartjs.org/docs/latest/getting-started/v3-migration.html)

There may be some changes in the way you import the Chat JS javascript
depending on the way you have implemented it in your application. You
can read more about integration on the [Chart JS
Documentation](https://www.chartjs.org/docs/master/getting-started/integration.html)

## Usage

### Basic usage

```php
use practically\chartjs\Chart;

echo Chart::widget([
    'type' => Chart::TYPE_BAR,
    'datasets' => [
        [
            'data' => [
                'Label 1' => 10,
                'Label 2' => 20,
                'Label 3' => 30
            ]
        ]
    ]
]);
```

### Using a db query to define the data

```php
echo Chart::widget([
    'type' => Chart::TYPE_BAR,
    'datasets' => [
        [
            'query' => Model::find()
                ->select('type')
                ->addSelect('count(*) as data')
                ->groupBy('type')
                ->createCommand(),
            'labelAttribute' => 'type'
        ]
    ]
]);
```

### Using a db query with a scatter chart

```php
echo Chart::widget([
    'type' => Chart::TYPE_SCATTER,
    'datasets' => [
        [
            'query' => Model::find()
                ->select('type')
                ->addSelect('sum(column_one) as x')
                ->addSelect('sum(column_two) as y')
                ->groupBy('type')
                ->createCommand(),
            'labelAttribute' => 'type'
        ]
    ]
]);
```

</details>


### Adding dom options

```php
echo Chart::widget([
    ...

    'options' => [
        'class' => 'chart',
        'data-attribute' => 'my-value'
    ],

    ...
]);
```

### Adding client options

In the client options array you can define any property to be json encoded and
passed to the chart js constructor.

```php
echo Chart::widget([
    ...

    'clientOptions' => [
        'title' => [
            'display' => true,
            'text' => 'My New Title',
        ],
        'legend' => ['display' => false],
    ]

    ...
]);
```

### Formatting the y axes

<details>

<summary>Chart JS v2.x</summary>

```php
echo Chart::widget([
    ...

     'clientOptions' => [
        'scales' => [
            'yAxes' => [
                [
                    'ticks' => [
						'min' => 0,
						'max' => 100,
						'callback' => new JsExpression('function(value, index, values) {
                             return \'£\'+value;
                        }')
                    ],
					'scaleLabel' => [
						'display' => true,
						'labelString' => 'Average (%)',
					]
				]
			]
        ],
        'tooltips' => [
            'callbacks' => [
                'label' => new JsExpression('function(tooltipItem, chart) {
                    var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || \'\';
                    return datasetLabel + \' £\'+tooltipItem.yLabel;
                }')
            ]
        ]
    ]

    ...
]);
```

</details>

<details>

<summary>Chart JS v3.x</summary>

```php
echo Chart::widget([
    ...

     'clientOptions' => [
        'scales' => [
            'y' => [
				'min' => 0,
				'max' => 100,
				'title' => [
					'display' => true,
					'text' => 'Average (%)',
				],
				'ticks' => [
					'callback' => new JsExpression('function(value, index, values) {
							return \'£\'+value;
					}')
				]
            ]
        ],
		'plugins' => [
			'tooltip' => [
				'callbacks' => [
					'label' => new JsExpression('function(context) {
						return \'£\'+context.chart.data.labels[context.dataIndex];
					}')
				]
			]
		]
    ]

    ...
]);
```

</details>

### Adding chart js events

```php
Chart::widget([
    'type' => Chart::TYPE_DOUGHNUT,
    'jsVar' => 'DoughnutChart',
    'jsEvents' => [
        'onclick' => new JsExpression('function(e) {
            var el = DoughnutChart.getElementAtEvent(e);
            window.location.href = "/search/ + el[0]._model.label;
        }')
    ]
]);
```

## Contributing

### Getting set up

Clone the repo and run `composer install`.
Then start hacking!

### Testing

All new features of bug fixes must be tested. Testing is with phpunit and can
be run with the following command

```bash
composer run-script test
```

### Coding Standards

This library uses psr2 coding standards and `squizlabs/php_codesniffer` for
linting. There is a composer script for this:

```bash
composer run-script lint
```

### Pull Requests

Before you create a pull request with you changes, the pre-commit script must
pass. That can be run as follows:

```bash
composer run-script pre-commit
```

## Credits

This package is created and maintained by [Practically.io](https://practically.io/)
