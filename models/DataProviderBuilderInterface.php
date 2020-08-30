<?php

namespace app\models;

use yii\data\SqlDataProvider;

interface DataProviderBuilderInterface
{
    public const SORT_QUERY_VAR_10D = '10days';
    public const SORT_QUERY_VAR_3D = '3days';
    public const SORT_QUERY_VAR_ALL = 'default';
    public const SORT_QUERY_VAR_2H = '2hours';
    public const SORT_QUERY_VAR_5D = '5days';

    public function build(string $sort): SqlDataProvider;
}
