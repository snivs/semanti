<?php

use yii\db\Schema;
use yii\db\Migration;

class m141022_115823_create_user_table extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id'                        => Schema::TYPE_PK,
            'username'                  => Schema::TYPE_STRING . ' NOT NULL',
            'email'                     => Schema::TYPE_STRING . ' NOT NULL',
            'password_hash'             => Schema::TYPE_STRING . ' NOT NULL',
            'status'                    => Schema::TYPE_SMALLINT . ' NOT NULL',
            'auth_key'                  => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_reset_token'      => Schema::TYPE_STRING,
            'account_activation_token'  => Schema::TYPE_STRING,
            'created_at'                => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'                => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        if ( !YII_ENV_PROD ) {
            $this->dropTable('{{%user}}');
        } else {
            echo "WARNING: " . __CLASS__ . " cannot be reverted.\n";
            return false;
        }
    }
}