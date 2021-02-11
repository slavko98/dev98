<?php
namespace Slavko98\Dev98\Model\ResourceModel\Post;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'post_id';
    protected $_eventPrefix = 'slavko98_dev98_post_collection';
    protected $_eventObject = 'post_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Slavko98\Dev98\Model\Post', 'Slavko98\Dev98\Model\ResourceModel\Post');
    }
}