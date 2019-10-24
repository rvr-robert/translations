<?php
namespace Custom\Translations\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

class Delete extends \Custom\Translations\Controller\Adminhtml\Translations
{
    /**
     * @var \Amasty\MultiInventory\Api\WarehouseRepositoryInterface
     */
    private $repository;

    /**
     * Delete constructor
     *
     * @param Action\Context $context
     * @param \Custom\Translations\Api\TranslationsRepositoryInterface $repository
     */
    public function __construct(
        Action\Context $context,
        \Custom\Translations\Api\TranslationsRepositoryInterface $repository
    ) {
        parent::__construct($context);
        $this->repository = $repository;
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('key_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->repository->getById($id);
                if (!$model->getIsGeneral()) {
                    $this->repository->deleteById($id);
                    $this->messageManager->addSuccessMessage(__('You deleted the string.'));
                } else {
                    $this->messageManager->addErrorMessage(__('We can\'t delete a string'));
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['key_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a string to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
