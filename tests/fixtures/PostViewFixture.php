<?php

namespace app\tests\fixtures;

use app\models\PostView;
use yii\test\ActiveFixture;

class PostViewFixture extends ActiveFixture
{
    public $modelClass = PostView::class;
    public $dataFile = __DIR__ . '/../_data/post_view.php';
    public $depends = [PostFixture::class,];
}
