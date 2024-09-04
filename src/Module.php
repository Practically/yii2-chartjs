<?php

declare(strict_types=1);

namespace practically\chartjs;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module as BaseModule;

/**
 * Use of this source is governed by a BSD-style
 * licence that can be found in the LICENCE file or at
 * https://www.practically.io/copyright/
 *
 * @copyright 2024 Practically.io. All rights reserved
 * @package practically/chartjs
 * @since 1.3.0
 */
class Module extends BaseModule implements BootstrapInterface
{

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        Yii::setAlias('@chartjs', __DIR__);
    }
}
