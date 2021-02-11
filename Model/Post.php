<?php
namespace Slavko98\Dev98\Model;

class Post extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'slavko98_dev98_post';
    protected $_cacheTag = 'slavko98_dev98_post';
    protected $_eventPrefix = 'slavko98_dev98_post';

    protected function _construct()
    {
        $this->_init('Slavko98\Dev98\Model\ResourceModel\Post');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}