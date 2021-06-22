<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\migrations;

use yii\db\Migration;

class m210622_152457_add_order_fields extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%form_builder__section}}',
            'order',
            $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->after('default_number_of_cols')
        );
        $this->addColumn(
            '{{%form_builder__field}}',
            'order',
            $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->after('relation_display_template')
        );
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%form_builder__section}}', 'order');
        $this->dropColumn('{{%form_builder__field}}', 'order');
    }
}
