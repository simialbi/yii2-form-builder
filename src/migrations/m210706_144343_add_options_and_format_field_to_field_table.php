<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\migrations;

use yii\db\Migration;

class m210706_144343_add_options_and_format_field_to_field_table extends Migration
{
    /**
     * {@inheritDoc}
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%form_builder__field}}',
            'format',
            $this->string(255)->null()->defaultValue(null)->after('multiple')
        );
        $this->addColumn(
            '{{%form_builder__field}}',
            'options',
            $this->json()->null()->defaultValue(null)->after('format')
        );
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%form_builder__field}}', 'format');
        $this->dropColumn('{{%form_builder__field}}', 'options');
    }
}
