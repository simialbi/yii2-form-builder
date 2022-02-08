<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\migrations;

use yii\db\Migration;

class m220208_144322_add_number_of_cols_field_to_field_table extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%form_builder__field}}',
            'number_of_cols',
            $this->tinyInteger()->unsigned()->null()->defaultValue(null)->after('format')
        );
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%form_builder__field}}', 'number_of_cols');
    }
}
