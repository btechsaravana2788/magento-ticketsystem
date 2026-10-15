<?php

namespace CustomerSupport\Ticket\Block\Customer\Ticket;

use CustomerSupport\Ticket\Model\TicketFactory;
use Magento\Framework\View\Element\Template;

class Form extends Template
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
        $ticketId = (int)$this->getRequest()->getParam('id');
        if (!$ticketId) {
            return $this->ticketFactory->create();
        }

        return $this->ticketFactory->create()->load($ticketId);
    }

    public function getSaveUrl(): string
    {
        return $this->getUrl('support/ticket/save');
    }
}
