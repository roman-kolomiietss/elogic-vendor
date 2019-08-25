<?php namespace Elogic\Vendor\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /**
         * Create table 'elogic_vendor'
         */

        $tableName = $installer->getTable('elogic_vendor');
        $tableComment = 'Elogic vendor table';
        $columns = [
            'entity_id'   => [
                'type'    => Table::TYPE_INTEGER,
                'size'    => null,
                'options' => ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'comment' => 'Vendor Id',
            ],
            'name'        => [
                'type'    => Table::TYPE_TEXT,
                'size'    => 255,
                'options' => ['nullable' => false, 'default' => ''],
                'comment' => 'Vendor name',
            ],
            'description' => [
                'type'    => Table::TYPE_TEXT,
                'size'    => 4096,
                'options' => ['nullable' => false, 'default' => ''],
                'comment' => 'Vendor description',
            ],
            'logo'        => [
                'type'    => Table::TYPE_TEXT,
                'size'    => 255,
                'options' => ['nullable' => false, 'default' => ''],
                'comment' => 'Vendor logo',
            ],
            'created_at'  => [
                'type'    => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                'size'    => null,
                'options' => ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'comment' => 'Created At',
            ],
            'updated_at'  => [
                'type'    => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                'size'    => null,
                'options' => ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'comment' => 'Updated At'
            ]
        ];

        $indexes = [// No index for this table
        ];

        // Table creation
        $table = $installer->getConnection()
                           ->newTable($tableName);

        // Columns creation
        foreach ($columns AS $name => $values) {
            $table->addColumn($name,
                $values['type'],
                $values['size'],
                $values['options'],
                $values['comment']);
        }

        // Indexes creation
        foreach ($indexes AS $index) {
            $table->addIndex($installer->getIdxName($tableName, array($index)),
                array($index));
        }

        // Table comment
        $table->setComment($tableComment);

        // Execute SQL to create the table
        $installer->getConnection()
                  ->createTable($table);

        // End Setup
        $installer->endSetup();
    }
}