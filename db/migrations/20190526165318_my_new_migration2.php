<?php

use Phinx\Migration\AbstractMigration;

class MyNewMigration2 extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
//    public function change()
//    {
//
//    }

    public function up() {
        $users = $this->table('users',['engine' => 'Innodb','id' => 'user_id','comment' => '用户表']);
        $users
            ->addColumn('username', 'string', ['limit' => 20,'comment' => '用户名'])
            ->addColumn('password', 'string', ['limit' => 40,'comment' => '密码'])
            ->addColumn('password_salt', 'string', ['limit' => 40,'comment'=> '盐值'])
            ->addColumn('email', 'string', ['limit' => 100])
            ->addColumn('first_name', 'string', ['limit' => 30])
            ->addColumn('remark', 'text', [])
            ->addColumn('created', 'timestamp',['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated', 'timestamp', ['null' => true,'default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['username', 'email'], ['unique' => true,'name' => 'idx_user_name_email'])
            ->save();
    }
}
