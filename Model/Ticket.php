<?php

namespace CustomerSupport\Ticket\Model;

use Magento\Framework\Model\AbstractModel;

class Ticket extends AbstractModel
{
    public const STATUS_OPEN = 'open';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_CLOSED = 'closed';

    protected function _construct(): void
    {
        $this->_init(\CustomerSupport\Ticket\Model\ResourceModel\Ticket::class);
    }
}
