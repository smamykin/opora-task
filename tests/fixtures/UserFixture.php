<?php

namespace app\tests\fixtures;

use app\models\User;
use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = User::class;
    public $dataFile = __DIR__ . '/../_data/user.php';
}
