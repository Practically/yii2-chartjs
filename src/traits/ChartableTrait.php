<?php

declare(strict_types=1);

namespace practically\chartjs\traits;

/**
 * Defines the properties of a chartable object
 *
 * @copyright 2024 Practically.io. All rights reserved
 * @package practically/chartjs
 * @since 2.0.0
 */
trait ChartableTrait
{

    /**
     * @var string
     */
    public string $label = '';

    /**
     * @var mixed
     */
    public $data;

}
