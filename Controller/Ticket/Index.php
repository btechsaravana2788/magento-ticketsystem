<?php

namespace CustomerSupport\Ticket\Controller\Ticket;

class Index extends AbstractTicket
{
    public function execute()
    {
        if (!$this->getCustomerId()) {
            return $this->loginRedirect();
        }

        return $this->pageFactory->create();
    }
}
