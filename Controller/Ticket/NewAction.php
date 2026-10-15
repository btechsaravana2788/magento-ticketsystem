<?php

namespace CustomerSupport\Ticket\Controller\Ticket;

class NewAction extends AbstractTicket
{
    public function execute()
    {
        if (!$this->getCustomerId()) {
            return $this->loginRedirect();
        }

        return $this->pageFactory->create();
    }
}
