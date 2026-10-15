<?php

namespace CustomerSupport\Ticket\Model\ResourceModel\Ticket;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(
            \CustomerSupport\Ticket\Model\Ticket::class,
            \CustomerSupport\Ticket\Model\ResourceModel\Ticket::class
        );
    }
}
