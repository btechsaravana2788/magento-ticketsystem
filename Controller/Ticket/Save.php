<?php

namespace CustomerSupport\Ticket\Controller\Ticket;

use Magento\Framework\App\Action\HttpPostActionInterface;

class Save extends AbstractTicket implements HttpPostActionInterface
{
    public function execute()
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) {
            return $this->loginRedirect();
        }

        $ticketId = (int)$this->getRequest()->getParam('ticket_id');
        $ticket = $ticketId ? $this->getOwnedTicket($ticketId, $customerId) : null;
        if ($ticketId && !$ticket) {
            $this->messageManager->addErrorMessage(__('The requested ticket could not be found.'));
            return $this->indexRedirect();
        }

        $subjectValue = $this->getRequest()->getParam('subject');
        $descriptionValue = $this->getRequest()->getParam('description');
        $subject = is_string($subjectValue) ? trim($subjectValue) : '';
        $description = is_string($descriptionValue) ? trim($descriptionValue) : '';
        if ($subject === '' || mb_strlen($subject) > 255 || $description === '') {
            $this->messageManager->addErrorMessage(
                __('Enter a subject (up to 255 characters) and a description for your ticket.')
            );
            return $this->resultRedirectFactory->create()->setPath(
                $ticketId ? 'support/ticket/edit' : 'support/ticket/new',
                $ticketId ? ['id' => $ticketId] : []
            );
        }

        if (!$ticket) {
            $ticket = $this->ticketFactory->create();
            $ticket->setCustomerId($customerId);
            $ticket->setStatus(\CustomerSupport\Ticket\Model\Ticket::STATUS_OPEN);
        }
        $ticket->setSubject($subject);
        $ticket->setDescription($description);

        try {
            $this->ticketResource->save($ticket);
            $this->messageManager->addSuccessMessage(__('Your support ticket has been saved.'));
            return $this->resultRedirectFactory->create()->setPath(
                'support/ticket/view',
                ['id' => (int)$ticket->getId()]
            );
        } catch (\Exception $exception) {
            $this->messageManager->addExceptionMessage($exception, __('The ticket could not be saved.'));
            return $this->resultRedirectFactory->create()->setPath(
                $ticketId ? 'support/ticket/edit' : 'support/ticket/new',
                $ticketId ? ['id' => $ticketId] : []
            );
        }
    }
}
