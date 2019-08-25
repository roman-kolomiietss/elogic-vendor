<?php

namespace Elogic\Vendor\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Vendor extends AbstractDb
{

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        // Table Name and Primary Key column
        $this->_init('elogic_vendor', 'entity_id');
    }

}