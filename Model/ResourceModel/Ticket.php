<?php

namespace CustomerSupport\Ticket\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Ticket extends AbstractDb
{
    protected function _construct(): void
    {
        $this->_init('customer_support_ticket', 'ticket_id');
    }
}
