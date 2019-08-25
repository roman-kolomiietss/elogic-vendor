<?php

namespace Elogic\Vendor\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

class Edit extends Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var \Elogic\Vendor\Model\Vendor
     */
    protected $_model;

    /**
     * @var \Magento\Framework\Image\AdapterFactory
     */
    protected $adapterFactory;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $uploader;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Elogic\Vendor\Model\Vendor $model
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Elogic\Vendor\Model\Vendor $model
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->_model = $model;
        parent::__construct($context);
    }

    /**
     * Edit Vendor
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()
                   ->getParam('id');
        $model = $this->_model;

        // If you have got an id, it's edition
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This vendor not exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_getSession()
                     ->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('vendor_index', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb($id ? __('Edit Vendor') : __('New Vendor'),
            $id ? __('Edit Vendor') : __('New Vendor'));
        $resultPage->getConfig()
                   ->getTitle()
                   ->prepend(__('Vendors'));
        $resultPage->getConfig()
                   ->getTitle()
                   ->prepend($model->getId() ? $model->getName() : __('New Vendor'));

        return $resultPage;
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Elogic_Vendor::vendor')
                   ->addBreadcrumb(__('Vendor'), __('Vendor'))
                   ->addBreadcrumb(__('Manage Vendors'), __('Manage Vendors'));
        return $resultPage;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Elogic_Vendor::vendor_save');
    }
}