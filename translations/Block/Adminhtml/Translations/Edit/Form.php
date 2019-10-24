<?php
namespace Custom\Translations\Block\Adminhtml\Translations\Edit;

use Magento\Store\Model\ScopeInterface;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{

    /**
     * @var ScopeInterface $scopeConfig
     */
    protected $scopeConfig;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    )
    {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Function prepare form for add/edit translation
     *
     * @return \Magento\Backend\Block\Widget\Form\Generic
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('row_data');
        $form = $this->_formFactory->create(
            ['data' => [
                'id' => 'edit_form',
                'enctype' => 'multipart/form-data',
                'action' => $this->getData('action'),
                'method' => 'post'
            ]
            ]
        );
        $form->setHtmlIdPrefix('translations_');
        if ($model->getId()) {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Edit Row Data'), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('key_id', 'hidden', ['name' => 'key_id']);
        } else {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Add Row Data'), 'class' => 'fieldset-wide']
            );
        }
        $storeManagerDataList = $this->_storeManager->getStores();
        $options = [];
        foreach ($storeManagerDataList as $store) {
            $localeCode = $this->scopeConfig->getValue('general/locale/code', ScopeInterface::SCOPE_STORE, $store->getStoreId());
            if(!in_array($localeCode, $options)){
                $options[$store->getStoreId()] = $localeCode;
            }
        }
        $fieldset->addField(
            'string',
            'text',
            [
                'name' => 'string',
                'label' => __('String'),
                'id' => 'string',
                'title' => __('String'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );
        $fieldset->addField(
            'store_id',
            'select',
            [
                'name' => 'store_id',
                'label' => __('Store Id'),
                'values' => $options,
                'required' => true,
            ]
        );
        $fieldset->addField(
            'translate',
            'text',
            [
                'name' => 'translate',
                'label' => __('Translate'),
                'required' => true,
            ]
        );
        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}