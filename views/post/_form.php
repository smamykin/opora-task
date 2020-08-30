<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Post */
/* @var $form yii\widgets\ActiveForm */
/* @var $userOptions array */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(['options' => ['data-post_form' => '']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'author_id')
        ->hint('Выберете существующего автора')
        ->label('Автор')
        ->dropdownList(
            $userOptions ,
            [
                'prompt'=>'Выбрать автора',
                'onchange' => 'this.value === \'create_new\' ? $(\'[data-create_new_author\').show() : $(\'[data-create_new_author\').hide()',
            ]
        );?>

    <div class="form-group field-post-text" data-create_new_author style="display: none">
        <label class="control-label" for="post-text">Добавить нового</label>
        <?= Html::textInput($model->formName() . '[author_name]', '', ['class'=> 'form-control']) ?>
        <div class="help-block"></div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
