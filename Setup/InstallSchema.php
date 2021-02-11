<?php
namespace Slavko98\Dev98\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    )
    {
        $installer = $setup;
        $installer->startSetup();

        if (!$installer->tableExists('slavko98_dev98_post')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('slavko98_dev98_post')
            )
                ->addColumn(
                    'uid',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary'  => true,
                        'unsigned' => true,
                    ],
                    'Unique table id'
                )
                ->addColumn(
                    'post_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => false,
                        'nullable' => false,
                        'primary'  => false,
                        'unsigned' => true,
                    ],
                    'Original post ID'
                )
                ->addColumn(
                    'title',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Post Title'
                )
                ->addColumn(
                    'link',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Original post URL'
                )
                ->addColumn(
                    'post_description',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '2k',
                    [],
                    'Post Description'
                )
                ->addColumn(
                    'post_content',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '64k',
                    [],
                    'Post Content'
                )
                ->addColumn(
                    'tags',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '2k',
                    [],
                    'Post Tags'
                )
                ->addColumn(
                    'author',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    64,
                    [],
                    'Post Author'
                )
                ->addColumn(
                    'created_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                    null,
                    [],
                    'Created At'
                )
                ->setComment('Post Table');
            $installer->getConnection()->createTable($table);

            $installer->getConnection()->addIndex(
                $installer->getTable('slavko98_dev98_post'),
                $setup->getIdxName(
                    $installer->getTable('slavko98_dev98_post'),
                    ['title', 'link', 'post_description', 'post_content', 'tags'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['title', 'link', 'post_description', 'post_content', 'tags'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        }
        $installer->endSetup();
    }
}