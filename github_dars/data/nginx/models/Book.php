<?php

namespace app\models;

use Yii;
use app\models\Author;
use yii\db\Expression;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property int $author_id
 * @property string|null $title
 * @property string|null $publication_date
 *
 * @property Author $author
 */
class Book extends \yii\db\ActiveRecord
{
    /**
     *
     *   внедряю дополнительные поля - для передачи значений в формы
     *
     */
    public $genreBook;
    public $authorBook;
    public $authorCountry;

    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     *
     *   реализуем поведение для отображение времени создания или обновления
     *
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['publication_date'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['publication_date'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'publication_date'], 'string', 'max' => 255],
            ///[['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Author ID',
            'title' => 'Название Книги',
            'genreBook' => 'Жанр Книги',
            'authorBook' => 'Автор Книги',
            'authorCountry' => 'Страна Автора',
            'publication_date' => 'Publication Date',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }

    /**
     * Gets query for [[Genre]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGenres()
    {
        return $this->hasMany(Genre::class, ['book_id' => 'id']);
    }


    /**
     * Проверяется значение - есть ли такой автор в Базе или нет
     * если данный автор в базе есть - вернуть этот id
     * если нет - вернуть 0
     * принимает параметры - данные с формы виджета для создания Book
     * @params array
     * @return int
     */
    public static function checkAuthor($data)
    {
        if ($needId = Author::find()->where(['name' => $data])->one()) {
            return $needId->id;
        }
        return 0;
    }
}
