<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Authors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <? foreach ([ '2hours', '3days', '5days', '10days', 'default', ] as $i => $order) { ?>
        <?= $i ? '|' : ''?>
        <?= $dataProvider->getSort()->link($order); ?>
    <?}?>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            ob_start();
            ?>
            <div>
                <?=Html::a(Html::encode($model['name']), ['view', 'id' => $model['id']]);?>
                <span class="bg-warning" title="Просмотры"><?=$model['countOfView']?></span>
            </div>

            <?php return ob_get_clean();
        },
    ]) ?>


</div>
