<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $sorter \yii\data\Sort */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Post', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <? foreach ([ '2hours', '3days', '5days', '10days', 'default', ] as $i => $order) { ?>
        <?= $i ? '|' : ''?>
        <?= $sorter->link($order); ?>
    <?}?>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            ob_start();
            ?>
            <div>
                <?=Html::a(Html::encode($model['title']), ['view', 'id' => $model['id']]);?>
                <span class="bg-warning" title="Просмотры"><?=$model['countOfView']?></span>
            </div>

            <?php return ob_get_clean();
        },
    ]) ?>


</div>
