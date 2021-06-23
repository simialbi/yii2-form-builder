<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\migrations;

use yii\db\Migration;

class m210623_122108_remove_dependencies_and_add_validators extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('{{%form_builder__field_ibfk_2}}', '{{%form_builder__field}}');
        $this->dropColumn('{{%form_builder__field}}', 'dependency_id');
        $this->dropColumn('{{%form_builder__field}}', 'dependency_operator');
        $this->dropColumn('{{%form_builder__field}}', 'required');

        $this->createTable('{{%form_builder__validator}}', [
            'id' => $this->primaryKey()->unsigned(),
            'field_id' => $this->integer()->unsigned()->notNull(),
            'name' => $this->string(255)->notNull(),
            'class' => 'VARCHAR(512) CHARSET ascii COLLATE ascii_bin NULL DEFAULT NULL',
            'configuration' => $this->json()->null()->defaultValue(null)
        ]);
        $this->addForeignKey(
            '{{%form_builder__validator_ibfk_1}}',
            '{{%form_builder__validator}}',
            'field_id',
            '{{%form_builder__field}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('{{%form_builder__validator_ibfk_1}}', '{{%form_builder__validator}}');
        $this->dropTable('{{%form_builder__validator}}');

        $this->addColumn(
            '{{%form_builder__field}}',
            'required',
            $this->boolean()->notNull()->defaultValue(0)
        );
        $this->addColumn(
            '{{%form_builder__field}}',
            'dependency_id',
            $this->integer()->unsigned()->null()->defaultValue(null)->after('max')
                ->comment('Reference to another field. Means this field is only required when the other is filled.')
        );
        $this->addColumn(
            '{{%form_builder__field}}',
            'dependency_operator',
            $this->char(1)->null()->defaultValue(null)->after('dependency_id')
                ->comment('If operator is "!", this field is only required when the reference is not filled.')
        );
        $this->addForeignKey(
            '{{%form_builder__field_ibfk_2}}',
            '{{%form_builder__field}}',
            'dependency_id',
            '{{%form_builder__field}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }
}
