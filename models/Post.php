<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $text
 * @property int $author_id
 *
 * @property User $author
 * @property PostView[] $postViews
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['author_id', 'text', 'title'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'text' => 'Text',
            'author_id' => 'Author ID',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setAuthor(User $user)
    {
        $this->author_id = $user->id;

        return $this;
    }

    /**
     * Gets query for [[PostViews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPostViews()
    {
        return $this->hasMany(PostView::class, ['post_id' => 'id'])->inverseOf('post');
    }

    public function getPostViewsCount()
    {
        return PostView::find()
            ->select('COUNT(*) as count')
            ->where(['post_id' => $this->id])
            ->scalar();
    }
}
