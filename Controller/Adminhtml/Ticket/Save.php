<?php

namespace CustomerSupport\Ticket\Controller\Adminhtml\Ticket;

use CustomerSupport\Ticket\Model\TicketFactory;
use CustomerSupport\Ticket\Model\ResourceModel\Ticket as TicketResource;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;

class Save extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'CustomerSupport_Ticket::manage';

    private TicketFactory $ticketFactory;
    private TicketResource $ticketResource;
    private RedirectFactory $redirectFactory;

    public function __construct(
        Action\Context $context,
        TicketFactory $ticketFactory,
        TicketResource $ticketResource,
        RedirectFactory $redirectFactory
    ) {
        $this->ticketFactory = $ticketFactory;
        $this->ticketResource = $ticketResource;
        $this->redirectFactory = $redirectFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $ticket = $this->ticketFactory->create();
        $this->ticketResource->load($ticket, (int)$this->getRequest()->getParam('ticket_id'));
        if (!$ticket->getId()) {
            $this->messageManager->addErrorMessage(__('The ticket no longer exists.'));
            return $this->redirectFactory->create()->setPath('*/*/');
        }

        $statusValue = $this->getRequest()->getParam('status');
        $status = is_string($statusValue) ? $statusValue : '';
        $allowedStatuses = [
            \CustomerSupport\Ticket\Model\Ticket::STATUS_OPEN,
            \CustomerSupport\Ticket\Model\Ticket::STATUS_IN_PROGRESS,
            \CustomerSupport\Ticket\Model\Ticket::STATUS_CLOSED,
        ];
        if (!in_array($status, $allowedStatuses, true)) {
            $this->messageManager->addErrorMessage(__('Select a valid ticket status.'));
            return $this->redirectFactory->create()->setPath('*/*/edit', ['id' => $ticket->getId()]);
        }

        $reply = $this->getRequest()->getParam('admin_reply');
        $ticket->setStatus($status);
        $ticket->setAdminReply(is_string($reply) ? trim($reply) : '');
        try {
            $this->ticketResource->save($ticket);
            $this->messageManager->addSuccessMessage(__('The ticket has been updated.'));
        } catch (\Exception $exception) {
            $this->messageManager->addExceptionMessage($exception, __('The ticket could not be updated.'));
            return $this->redirectFactory->create()->setPath('*/*/edit', ['id' => $ticket->getId()]);
        }

        return $this->redirectFactory->create()->setPath('*/*/edit', ['id' => $ticket->getId()]);
    }
}
