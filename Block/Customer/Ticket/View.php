<?php

namespace CustomerSupport\Ticket\Block\Customer\Ticket;

use CustomerSupport\Ticket\Model\TicketFactory;
use Magento\Framework\View\Element\Template;

class View extends Template
{
    private TicketFactory $ticketFactory;

    public function __construct(
        Template\Context $context,
        TicketFactory $ticketFactory,
        array $data = []
    ) {
        $this->ticketFactory = $ticketFactory;
        parent::__construct($context, $data);
    }

    public function getTicket()
    {
        return $this->ticketFactory->create()->load((int)$this->getRequest()->getParam('id'));
    }
}
