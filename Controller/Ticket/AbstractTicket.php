<?php

namespace CustomerSupport\Ticket\Controller\Ticket;

use CustomerSupport\Ticket\Model\Ticket;
use CustomerSupport\Ticket\Model\TicketFactory;
use CustomerSupport\Ticket\Model\ResourceModel\Ticket as TicketResource;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

abstract class AbstractTicket extends Action
{
    protected CustomerSession $customerSession;
    protected TicketFactory $ticketFactory;
    protected TicketResource $ticketResource;
    protected PageFactory $pageFactory;

    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        TicketFactory $ticketFactory,
        TicketResource $ticketResource,
        PageFactory $pageFactory
    ) {
        $this->customerSession = $customerSession;
        $this->ticketFactory = $ticketFactory;
        $this->ticketResource = $ticketResource;
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    protected function getCustomerId(): ?int
    {
        if (!$this->customerSession->isLoggedIn()) {
            return null;
        }

        return (int)$this->customerSession->getCustomerId();
    }

    protected function loginRedirect()
    {
        $this->messageManager->addNoticeMessage(__('Please sign in to manage your support tickets.'));
        return $this->resultRedirectFactory->create()->setPath('customer/account/login');
    }

    protected function indexRedirect()
    {
        return $this->resultRedirectFactory->create()->setPath('support/ticket/index');
    }

    protected function getOwnedTicket(int $ticketId, int $customerId): ?Ticket
    {
        $ticket = $this->ticketFactory->create();
        $this->ticketResource->load($ticket, $ticketId);

        if (!$ticket->getId() || (int)$ticket->getCustomerId() !== $customerId) {
            return null;
        }

        return $ticket;
    }
}
