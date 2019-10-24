<?php
namespace Custom\Translations\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Json\DecoderInterface;
use Magento\Store\Model\ScopeInterface;

class Save extends \Custom\Translations\Controller\Adminhtml\Translations
{
    /**
     * @var DecoderInterface
     */
    protected $jsonDecoder;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \Custom\ServiceCenters\Api\ServiceCenterRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Custom\ServiceCenters\Model\ServiceCenterFactory
     */
    protected $factory;

    /**
     * @var \Amasty\MultiInventory\Helper\System
     */
    protected $amastySystem;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\Translate\ResourceInterface
     */
    protected $translateResource;

    /**
     * @var \Magento\Translation\Model\FileManager
     */
    protected $fileManager;

    /**
     * Save constructor.
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param \Custom\Translations\Api\TranslationsRepositoryInterface $repository
     * @param \Custom\Translations\Model\TranslationsFactory $factory
     * @param DecoderInterface $jsonDecoder
     */
    public function __construct(

        \Magento\Framework\Translate\ResourceInterface $translateResource,
        \Magento\Translation\Model\FileManager $fileManager,
        Context $context,
        DataPersistorInterface $dataPersistor,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Custom\Translations\Api\TranslationsRepositoryInterface $repository,
        \Custom\Translations\Model\TranslationsFactory $factory,
        DecoderInterface $jsonDecoder
    ) {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->translateResource = $translateResource;
        $this->fileManager = $fileManager;
        $this->scopeConfig = $scopeConfig;
        $this->repository = $repository;
        $this->factory = $factory;
        $this->jsonDecoder = $jsonDecoder;
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $id = $this->getRequest()->getParam('key_id');
            if (empty($data['key_id'])) {
                $data['key_id'] = null;
            }
            if (!$id) {
                $model = $this->factory->create();
            } else {
                $model = $this->repository->getById($id);
                if (!$model->getId() && $id) {
                    $this->messageManager->addErrorMessage(__('This translation no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }
            $data['locale'] = $this->scopeConfig->getValue('general/locale/code', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $data['store_id']);
            $data['crc_string'] = crc32($data['string']);
            $model->setData($data);
            try {
                if (!$id) {
                    $this->repository->save($model);
                }
                $this->repository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the translation.'));
                $this->dataPersistor->clear('custom_translations_translation');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/addrow', ['key_id' => $model->getId()]);
                }
                $translations = $this->translateResource->getTranslationArray($data['store_id'], $data['locale']);
                $this->fileManager->updateTranslationFileContent(json_encode($translations), $data['locale']);
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving translation.'));
            }

            $this->dataPersistor->set('custom_translations_translation', $data);
            return $resultRedirect->setPath(
                '*/*/addrow',
                ['key_id' => $this->getRequest()->getParam('key_id')]
            );
        }

        return $resultRedirect->setPath('*/*/');
    }
}
