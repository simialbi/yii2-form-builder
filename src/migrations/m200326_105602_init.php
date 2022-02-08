<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\migrations;

use yii\db\Migration;

class m200326_105602_init extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%form_builder__form}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(255)->notNull(),
            'layout' => $this->string()->notNull()->defaultValue('default')
                             ->comment('Possible values: default, floatingLabel, placeholder, horizontal'),
            'language' => $this->string(5)->notNull()->defaultValue('en-US'),
            'created_by' => $this->string(64)->null()->defaultValue(null),
            'updated_by' => $this->string(64)->null()->defaultValue(null),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull()
        ]);

        $this->createTable('{{%form_builder__section}}', [
            'id' => $this->primaryKey()->unsigned(),
            'form_id' => $this->integer()->unsigned()->notNull(),
            'name' => $this->string(255)->notNull(),
            'default_number_of_cols' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(2),
            'created_by' => $this->string(64)->null()->defaultValue(null),
            'updated_by' => $this->string(64)->null()->defaultValue(null),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull()
        ]);
        $this->createTable('{{%form_builder__field}}', [
            'id' => $this->primaryKey()->unsigned(),
            'section_id' => $this->integer()->unsigned()->notNull(),
            'name' => $this->string(255)->notNull(),
            'label' => $this->string(255)->null()->defaultValue(null),
            'type' => $this->string(255)->notNull()->defaultValue('string')
                           ->comment('Possible values: string, text, int, double, date, time, datetime, select, checkbox, radio, file'),
            'default_value' => $this->string(255)->null()->defaultValue(null),
            'required' => $this->boolean()->notNull()->defaultValue(0),
            'multiple' => $this->boolean()->notNull()->defaultValue(0),
            'min' => $this->smallInteger()->unsigned()->null()->defaultValue(null),
            'max' => $this->smallInteger()->unsigned()->null()->defaultValue(null),
            'dependency_id' => $this->integer()->unsigned()->null()->defaultValue(null)
                                    ->comment('Reference to another field. Means this field is only required when the other is filled.'),
            'dependency_operator' => $this->char(1)->null()->defaultValue(null)
                                          ->comment('If operator is "!", this field is only required when the reference is not filled.'),
            'relation_model' => 'VARCHAR(512) CHARSET ascii COLLATE ascii_bin NULL DEFAULT NULL',
            'relation_field' => 'VARCHAR(255) CHARSET ascii COLLATE ascii_bin NULL DEFAULT NULL',
            'relation_display_template' => $this->string(1024)->null()->defaultValue(null),
            'created_by' => $this->string(64)->null()->defaultValue(null),
            'updated_by' => $this->string(64)->null()->defaultValue(null),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull()
        ]);

        $this->createIndex(
            '{{%form_builder__form-name-language}}',
            '{{%form_builder__form}}',
            ['name', 'language'],
            true
        );

        $this->addForeignKey(
            '{{%form_builder__section_ibfk_1}}',
            '{{%form_builder__section}}',
            'form_id',
            '{{%form_builder__form}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%form_builder__field_ibfk_1}}',
            '{{%form_builder__field}}',
            'section_id',
            '{{%form_builder__section}}',
            'id',
            'CASCADE',
            'CASCADE'
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

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%form_builder__field_ibfk_2}}', '{{%form_builder__field}}');
        $this->dropForeignKey('{{%form_builder__field_ibfk_1}}', '{{%form_builder__field}}');
        $this->dropForeignKey('{{%form_builder__section_ibfk_1}}', '{{%form_builder__section}}');

        $this->dropTable('{{%form_builder__field}}');
        $this->dropTable('{{%form_builder__section}}');
        $this->dropTable('{{%form_builder__form}}');
    }
}
