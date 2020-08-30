<?php

namespace app\controllers;

use Throwable;
use Yii;
use app\models\Post;
use app\models\User;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider(
            [
                'query' => Post::find(),
            ]
        );

        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id)
    {
        return $this->render(
            'view',
            [
                'model' => $this->findModel($id),
            ]
        );
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws InvalidConfigException
     */
    public function actionCreate()
    {
        $model = new Post();

        $data = $this->getFormDataFromRequest($model);

        if ($model->load($data) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render(
            'create',
            [
                'model' => $model,
                'userOptions' => $this->getUserOptions(),
            ]
        );
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        $data = $this->getFormDataFromRequest($model);

        if ($model->load($data) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render(
            'update',
            [
                'model' => $model,
                'userOptions' => $this->getUserOptions(),
            ]
        );
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete(int $id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $newName
     * @return User
     */
    private function getAuthor($newName)
    {
        $newUser = User::findOne(['name' => $newName ?: User::UNKNOWN_NAME]);
        if (empty($newUser)) {
            $newUser = new User();
            $newUser->name = $newName;
            $newUser->save();
        }
        return $newUser;
    }

    /**
     * @return array|string[]
     */
    private function getUserOptions()
    {
        $userOptions = User::find()
            ->select(['name', 'id'])
            ->indexBy('id')
            ->column();
        $userOptions += ['create_new' => 'Добавить нового'];
        return $userOptions;
    }

    /**
     * @param array|null $formData
     * @return int|mixed
     */
    private function processAuthorId(?array $formData)
    {
        $authorId = $formData['author_id'];
        $newName = trim($formData['author_name']);
        if ('create_new' === $authorId) {
            $authorId = $this->getAuthor($newName)->id;
        }
        return $authorId;
    }

    /**
     * @param Post $model
     * @return array[]
     * @throws InvalidConfigException
     */
    private function getFormDataFromRequest(Post $model): ?array
    {
        $formData = Yii::$app->request->post('Post');
        if (empty($formData)){
            return null;
        }
        return [
            $model->formName() => [
                'author_id' => $this->processAuthorId($formData),
                'title' => $formData['title'],
                'text' => $formData['text'],
            ],
        ];
    }
}
