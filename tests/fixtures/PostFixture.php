<?php

namespace app\tests\fixtures;

use app\models\Post;
use yii\test\ActiveFixture;

class PostFixture extends ActiveFixture
{
    public $modelClass = Post::class;
    public $dataFile = __DIR__ . '/../_data/post.php';
    public $depends = [UserFixture::class,];
}
