<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%post_view}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%post}}`
 */
class m200830_064605_create_post_view_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%post_view}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
        ]);

        // creates index for column `post_id`
        $this->createIndex(
            '{{%idx-post_view-post_id}}',
            '{{%post_view}}',
            'post_id'
        );

        // add foreign key for table `{{%post}}`
        $this->addForeignKey(
            '{{%fk-post_view-post_id}}',
            '{{%post_view}}',
            'post_id',
            '{{%post}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%post}}`
        $this->dropForeignKey(
            '{{%fk-post_view-post_id}}',
            '{{%post_view}}'
        );

        // drops index for column `post_id`
        $this->dropIndex(
            '{{%idx-post_view-post_id}}',
            '{{%post_view}}'
        );

        $this->dropTable('{{%post_view}}');
    }
}
