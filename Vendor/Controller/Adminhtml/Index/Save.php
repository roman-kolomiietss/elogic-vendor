<?php

namespace Elogic\Vendor\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

class Save extends Action
{
    /**
     * @var \Elogic\Vendor\Model\Vendor
     */
    protected $_model;

    protected $_adapterFactory;

    protected $_uploader;

    protected $_filesystem;

    /**
     * @param Action\Context $context
     * @param \Elogic\Vendor\Model\Vendor $model
     */
    public function __construct(
        Action\Context $context,
        \Elogic\Vendor\Model\Vendor $model,
        \Magento\Framework\Image\AdapterFactory $adapterFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploader,
        \Magento\Framework\Filesystem $filesystem
    ) {
        parent::__construct($context);
        $this->_model = $model;
        $this->_adapterFactory = $adapterFactory;
        $this->_uploader = $uploader;
        $this->_filesystem = $filesystem;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()
                     ->getPostValue();

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            /** @var \Elogic\Vendor\Model\Vendor $model */
            $model = $this->_model;

            $id = $this->getRequest()
                       ->getParam('id');
            if ($id) {
                $model->load($id);
            }

            $data = $this->_uploadLogo($data);

            $model->setData($data);

            $this->_eventManager->dispatch('vendor_index_prepare_save',
                ['vendor' => $model, 'request' => $this->getRequest()]);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('Vendor saved'));
                $this->_getSession()
                     ->setFormData(false);

                if ($this->getRequest()
                         ->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addWarningMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addWarningMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the vendor'));
            }

            $this->_getSession()
                 ->setFormData($data);
            return $resultRedirect->setPath('*/*/edit',
                [
                    'entity_id' => $this->getRequest()
                                        ->getParam('id')
                ]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Upload/save logo
     *
     * @param $data
     */
    protected function _uploadLogo($data)
    {
        if (isset($_FILES['logo']) && isset($_FILES['logo']['name']) && strlen($_FILES['logo']['name'])) {
            try {
                $base_media_path = 'vendor-logos';
                $uploader = $this->_uploader->create(['fileId' => 'logo']);
                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'png']);
                $imageAdapter = $this->_adapterFactory->create();
                $uploader->addValidateCallback('image', $imageAdapter, 'validateUploadFile');
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $mediaDirectory = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                $result = $uploader->save($mediaDirectory->getAbsolutePath($base_media_path));
                $data['logo'] = $base_media_path . $result['file'];
            } catch (\Exception $e) {
                if ($e->getCode() == 0) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                }
            }
        } elseif(isset($data['logo']['delete'])) {
            //TODO implement cleaning old/not used images
            $data['logo'] = '';
        } elseif(isset($data['logo']['value'])) {
            $data['logo'] = $data['logo']['value'];
        } else {
            $data['logo'] = '';
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Elogic_Vendor::vendor_save');
    }
}