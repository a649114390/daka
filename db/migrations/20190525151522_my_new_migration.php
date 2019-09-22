<?php

use Phinx\Migration\AbstractMigration;

class MyNewMigration extends AbstractMigration
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
        $table = $this->table('user');
//        $table->addColumn('user_id', 'integer', ['length' => '10'])
//            ->addColumn('vip_id', 'integer', ['length' => \Phinx\Db\Adapter\MysqlAdapter::INT_BIG])
//            ->update();
        $table->addColumn('city', 'string',['length' => 255])
            ->addIndex(['city'],[
                'unique' => true,
                'name' => 'idx_users_email'])
            ->update();
    }

    public function down() {

    }
}
