<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m170809_065819_create_users_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'username' => $this->string(20)->notNull()->unique(),
            'password' => $this->string(),
            'fio' => $this->string(),
            'accessToken' => $this->string(),
            'authKey' => $this->string(),
            'role' => $this->boolean(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('users');
    }
}
