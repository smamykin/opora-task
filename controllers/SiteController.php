<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * Выводит на главную немного комментав о том как это задание было выполнено.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
