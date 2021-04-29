<?php
namespace Excellence\Storepickup\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
		$installer = $setup;
		$installer->startSetup();
            /**
             * Creating table excellence_storebase
             */
            $installer->getConnection()->addColumn(
                $installer->getTable('excellence_storebase'),
                'is_storepickup',
                [
                    'nullable' => false,
                    'length' => 255,
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'default' => 0,
                    'comment' => __('Is Storepickup'),
                ]
            );
        $installer->endSetup();
	}
}