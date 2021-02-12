<?php
namespace Slavko98\Dev98\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.1.0', '<')) {
            $installer->getConnection()->addColumn(
                $installer->getTable('slavko98_dev98_post'),
                'status',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'nullable' => true,
                    'default' => 1,
                    'comment' => 'Post status (enabled/disabled)',
                    'after' => 'created_at'
                ]
            );
        }

        $installer->endSetup();
    }
}