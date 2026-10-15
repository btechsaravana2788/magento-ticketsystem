<?php

namespace CustomerSupport\Ticket\Controller\Ticket;

use Magento\Framework\App\Action\HttpPostActionInterface;

class Delete extends AbstractTicket implements HttpPostActionInterface
{
    public function execute()
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) {
            return $this->loginRedirect();
        }

        $ticket = $this->getOwnedTicket((int)$this->getRequest()->getParam('id'), $customerId);
        if (!$ticket) {
            $this->messageManager->addErrorMessage(__('The requested ticket could not be found.'));
            return $this->indexRedirect();
        }

        try {
            $this->ticketResource->delete($ticket);
            $this->messageManager->addSuccessMessage(__('The support ticket has been deleted.'));
        } catch (\Exception $exception) {
            $this->messageManager->addExceptionMessage($exception, __('The ticket could not be deleted.'));
        }

        return $this->indexRedirect();
    }
}
