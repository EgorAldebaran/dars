<?php

use yii\base\Security;
use yii\db\Migration;
use Yii;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m230209_084213_create_user_table extends Migration
{
    private Security $security;
    public function __construct(Security $security, $config = [])
    {
        parent::__construct($config);
        $this->security = $security;
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->integer()->notNull(),
            'email' => $this->string(),
            'password' => $this->string(),
            'roles' => $this->string(),
        ]);

        $this->insert('{{%user}}', [
            'id' => 1,
            'email' => 'jacke@gmail.com',
            'password' => $this->security->generatePasswordHash('userpassword'),
            'roles' => 'USER',
        ]);

        $this->insert('{{%user}}', [
            'id' => 2,
            'email' => 'queen@gmail.com',
            'password' => $this->security->generatePasswordHash('adminpassword'),
            'roles' => 'ADMIN',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%user}}', ['id' => 1]);
        $this->delete('{{%user}}', ['id' => 2]);
        $this->dropTable('{{%user}}');
    }
}
