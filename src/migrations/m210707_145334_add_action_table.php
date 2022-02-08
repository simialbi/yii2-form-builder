<?php
/**
 * @package yii2-form-builder
 * @author Simon Karlen <simi.albi@gmail.com>
 */

namespace simialbi\yii2\formbuilder\migrations;

use yii\db\Migration;
use yii\helpers\Json;

class m210707_145334_add_action_table extends Migration
{
    /**
     * {@inheritDoc}
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $this->dropColumn('{{%form_builder__field}}', 'multiple');

        $this->createTable('{{%form_builder__action}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(255)->notNull(),
            'translation_category' => $this->string(255)->null()->defaultValue(null),
            'class' => 'VARCHAR(512) CHARSET ascii COLLATE ascii_bin NULL DEFAULT NULL',
            'properties' => $this->json()->notNull()->defaultValue('{}')
        ]);
        $this->createTable('{{%form_builder__form_action}}', [
            'form_id' => $this->integer()->unsigned()->notNull(),
            'action_id' => $this->integer()->unsigned()->notNull(),
            'configuration' => $this->json()->notNull()->defaultValue('{}'),
            'PRIMARY KEY ([[form_id]], [[action_id]])'
        ]);

        $this->addForeignKey(
            '{{%form_builder__form_action_ibfk1}}',
            '{{%form_builder__form_action}}',
            'form_id',
            '{{%form_builder__form}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            '{{%form_builder__form_action_ibfk2}}',
            '{{%form_builder__form_action}}',
            'action_id',
            '{{%form_builder__action}}',
            'id',
            'NO ACTION',
            'CASCADE'
        );

        $this->batchInsert('{{%form_builder__action}}', ['id', 'name', 'class', 'properties'], [
            [1, 'Send mail', '\simialbi\yii2\formbuilder\actions\SendMailAction', Json::encode([
                'title' => 'Settings',
                'type' => 'object',
                'required' => ['recipients', 'mailer'],
                'properties' => [
                    'sender' => [
                        'title' => 'Sender',
                        'description' => 'The sender of the mail',
                        'type' => 'object',
                        'format' => 'grid-strict',
                        'properties' => [
                            'name' => [
                                'title' => 'Name',
                                'type' => 'string',
                                'options' => [
                                    'grid_columns' => 4
                                ]
                            ],
                            'email' => [
                                'title' => 'E-Mail',
                                'type' => 'string',
                                'format' => 'email',
                                'required' => 'true',
                                'options' => [
                                    'grid_columns' => 8
                                ]
                            ]
                        ]
                    ],
                    'recipients' => [
                        'title' => 'Recipients',
                        'description' => 'One or more mail addresses of the recipient(s).',
                        'type' => 'array',
                        'format' => 'table',
                        'uniqueItems' => true,
                        'items' => [
                            'type' => 'object',
                            'title' => 'Recipient',
                            'properties' => [
                                'name' => [
                                    'title' => 'Name',
                                    'type' => 'string'
                                ],
                                'email' => [
                                    'title' => 'E-Mail',
                                    'type' => 'string',
                                    'format' => 'email',
                                    'required' => true
                                ]
                            ]
                        ]
                    ],
                    'mailer' => [
                        'title' => 'Mailer component name',
                        'description' => 'The mailer component name',
                        'default' => 'mail',
                        'type' => 'string'
                    ]
                ]
            ])],
            [2, 'Save', '\simialbi\yii2\formbuilder\actions\SaveAction', '{}'],
            [3, 'Generate pdf', '\simialbi\yii2\formbuilder\actions\GeneratePdfAction', Json::encode([
                'title' => 'Settings',
                'type' => 'object',
                'required' => ['template'],
                'properties' => [
                    'template' => [
                        'title' => 'Template',
                        'description' => 'The path to the template view to use as base. Alias\' can be used.',
                        'type' => 'string'
                    ]
                ]
            ])]
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->addColumn(
            '{{%form_builder__field}}',
            'multiple',
            $this->boolean()->notNull()->defaultValue(0)->after('type')
        );
        $this->dropForeignKey('{{%form_builder__form_action_ibfk2}}', '{{%form_builder__form_action}}');
        $this->dropForeignKey('{{%form_builder__form_action_ibfk1}}', '{{%form_builder__form_action}}');
        $this->dropTable('{{%form_builder__form_action}}');
        $this->dropTable('{{%form_builder__action}}');
    }
}
