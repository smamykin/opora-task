<?php

namespace app\models;

use yii\data\SqlDataProvider;

interface DataProviderBuilderInterface
{
    public function build(string $sort): SqlDataProvider;
}
