<?php

namespace Elogic\Vendor\Block\Adminhtml;

use Magento\Backend\Block\Widget\Form\Container;

class Edit extends Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Get header with Vendor name
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('vendor_index')
                                ->getId()) {
            return __("Edit Vendor '%1'",
                $this->escapeHtml($this->_coreRegistry->registry('vendor_index')
                                                      ->getName()));
        } else {
            return __('New Vendor');
        }
    }

    /**
     * Vendor edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'entity_id';
        $this->_blockGroup = 'Elogic\Vendor';
        $this->_controller = 'adminhtml';

        parent::_construct();

        if ($this->_isAllowedAction('Elogic_vendo::vendor_save')) {
            $this->buttonList->update('save', 'label', __('Save Vendor'));
            $this->buttonList->add('saveandcontinue',
                [
                    'label'          => __('Save and Continue Edit'),
                    'class'          => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                -100);
        } else {
            $this->buttonList->remove('save');
        }

    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('index/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }
}