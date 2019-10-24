<?php
namespace Custom\Translations\Model\ResourceModel\Translations;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Custom\Translations\Model\Translations', 'Custom\Translations\Model\ResourceModel\Translations');
    }
}
