<?php
namespace Custom\Translations\Model\ResourceModel;

use Magento\Translation\App\Config\Type\Translation;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Config;

class Translate extends \Magento\Translation\Model\ResourceModel\Translate
{
    /**
     * @var Config
     */
    protected $appConfig;

    public function __construct(
        Config $appConfig,
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\App\ScopeResolverInterface $scopeResolver,
        $connectionName = null,
        $scope = null
    )
    {
        $this->appConfig = $appConfig;
        parent::__construct(
            $context,
            $scopeResolver,
            $connectionName,
            $scope);
    }

    /**
     * Retrieve translation array for store / locale code
     *
     * @param null $storeId
     * @param null $locale
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getTranslationArray($storeId = null, $locale = null)
    {
        if ($storeId === null) {
            $storeId = $this->getStoreId();
        }
        $locale = (string) $locale;

        $data = $this->getAppConfig()->get(
            Translation::CONFIG_TYPE,
            $locale . '/' . $this->getStoreCode($storeId),
            []
        );
        $connection = $this->getConnection();
        if ($connection) {
            $select = $connection->select()
                ->from($this->getMainTable(), ['string', 'translate'])
                ->where('locale = :locale')
                ->order('store_id');
            $bind = [':locale' => $locale];
            $dbData = $connection->fetchPairs($select, $bind);
            $data = array_replace($data, $dbData);
        }
        return $data;
    }

    /**
     * Retrieve store code by store id
     *
     * @param int $storeId
     * @return string
     */
    private function getStoreCode($storeId)
    {
        return $this->scopeResolver->getScope($storeId)->getCode();
    }

    /**
     * @deprecated 100.1.2
     * @return Config
     */
    public function getAppConfig()
    {
        if ($this->appConfig === null) {
            $this->appConfig = ObjectManager::getInstance()->get(Config::class);
        }
        return $this->appConfig;
    }

}