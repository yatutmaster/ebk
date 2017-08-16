<?php

use yii\db\Migration;

/**
 * Handles the creation of table `data_users`.
 * Has foreign keys to the tables:
 *
 * - `users`
 */
class m170809_070528_create_data_users_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('data_users', [
            'id' => $this->primaryKey(),
            'req' => $this->text(),
            'res' => $this->string(255),
            'user_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-data_users-user_id',
            'data_users',
            'user_id'
        );

        // add foreign key for table `users`
        $this->addForeignKey(
            'fk-data_users-user_id',
            'data_users',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `users`
        $this->dropForeignKey(
            'fk-data_users-user_id',
            'data_users'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-data_users-user_id',
            'data_users'
        );

        $this->dropTable('data_users');
    }
}
