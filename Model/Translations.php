<?php
namespace Custom\Translations\Model;

class Translations extends \Magento\Framework\Model\AbstractModel implements \Custom\Translations\Api\Data\TranslationsInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'translation';

    protected function _construct()
    {
        $this->_init('Custom\Translations\Model\ResourceModel\Translations');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
