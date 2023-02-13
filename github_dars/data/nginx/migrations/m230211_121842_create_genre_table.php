<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%genre}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%book}}`
 */
class m230211_121842_create_genre_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%genre}}', [
            'id' => $this->primaryKey(),
            'book_id' => $this->integer()->notNull(),
            'title' => $this->string(),
        ]);

        // creates index for column `book_id`
        $this->createIndex(
            '{{%idx-genre-book_id}}',
            '{{%genre}}',
            'book_id'
        );

        // add foreign key for table `{{%book}}`
        $this->addForeignKey(
            '{{%fk-genre-book_id}}',
            '{{%genre}}',
            'book_id',
            '{{%book}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%book}}`
        $this->dropForeignKey(
            '{{%fk-genre-book_id}}',
            '{{%genre}}'
        );

        // drops index for column `book_id`
        $this->dropIndex(
            '{{%idx-genre-book_id}}',
            '{{%genre}}'
        );

        $this->dropTable('{{%genre}}');
    }
}
