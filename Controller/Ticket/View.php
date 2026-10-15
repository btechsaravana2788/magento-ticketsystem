<?php

namespace CustomerSupport\Ticket\Controller\Ticket;

class View extends AbstractTicket
{
    public function execute()
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) {
            return $this->loginRedirect();
        }

        if (!$this->getOwnedTicket((int)$this->getRequest()->getParam('id'), $customerId)) {
            $this->messageManager->addErrorMessage(__('The requested ticket could not be found.'));
            return $this->indexRedirect();
        }

        return $this->pageFactory->create();
    }
}
