<?php

namespace Elogic\Vendor\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

class Delete extends Action
{
    protected $_model;

    /**
     * @param Action\Context $context
     * @param \Elogic\Vendor\Model\Vendor $model
     */
    public function __construct(
        Action\Context $context,
        \Elogic\Vendor\Model\Vendor $model
    ) {
        parent::__construct($context);
        $this->_model = $model;
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()
                   ->getParam('id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->_model;
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('Vendor deleted'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('Vendor does not exist'));
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Elogic_Vendor::vendor_delete');
    }
}