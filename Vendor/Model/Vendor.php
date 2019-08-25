<?php

namespace Elogic\Vendor\Model;

use \Magento\Framework\Model\AbstractModel;

class Vendor extends AbstractModel
{
    const VENDOR_ID = 'entity_id'; // We define the id fieldname

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'vendor'; // parent value is 'core_abstract'

    /**
     * Name of object id field
     *
     * @var string
     */
    protected $_idFieldName = self::VENDOR_ID; // parent value is 'id'

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Elogic\Vendor\Model\ResourceModel\Vendor');
    }

}