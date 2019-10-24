<?php
namespace Custom\Translations\Model\ResourceModel;

class Translations extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('translation','key_id');
    }
}
