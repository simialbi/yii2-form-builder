<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\migrations;

use yii\db\Migration;

class m210705_095423_remove_default_value_and_min_max extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%form_builder__field}}', 'min');
        $this->dropColumn('{{%form_builder__field}}', 'max');
        $this->dropColumn('{{%form_builder__field}}', 'default_value');
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->addColumn(
            '{{%form_builder__field}}',
            'min',
            $this->smallInteger()->unsigned()->null()->defaultValue(null)->after('multiple')
        );
        $this->addColumn(
            '{{%form_builder__field}}',
            'max',
            $this->smallInteger()->unsigned()->null()->defaultValue(null)->after('min')
        );
        $this->addColumn(
            '{{%form_builder__field}}',
            'default_value',
            $this->string(255)->null()->defaultValue(null)->after('type')
        );
    }
}
