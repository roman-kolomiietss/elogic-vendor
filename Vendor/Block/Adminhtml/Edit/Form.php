<?php

namespace Elogic\Vendor\Block\Adminhtml\Edit;

use \Magento\Backend\Block\Widget\Form\Generic;

class Form extends Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('vendor_form');
        $this->setTitle(__('Vendor Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Elogic\Vendor\Model\Vendor $model */
        $model = $this->_coreRegistry->registry('vendor_index');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'enctype' => 'multipart/form-data', 'method' => 'post']]);

        $form->setHtmlIdPrefix('vendor_');

        $fieldset = $form->addFieldset('base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']);

        if ($model->getId()) {
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        }

        $fieldset->addField('name',
            'text',
            ['name' => 'name', 'label' => __('Name'), 'title' => __('Name'), 'required' => true]);

        $fieldset->addField('description',
            'textarea',
            ['name' => 'description', 'label' => __('Description'), 'title' => __('Description'), 'required' => true]);

        $fieldset->addField('logo',
            'image',
            [
                'name'           => 'logo',
                'label'          => __('My Image'),
                'title'          => __('My Image'),
                'class'          => 'logo_image',
                'data-form-part' => $this->getData('target_form'),
                'note'           => __('Allowed image types: jpg,png')
            ]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}