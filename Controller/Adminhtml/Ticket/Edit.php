<?php

namespace CustomerSupport\Ticket\Controller\Adminhtml\Ticket;

use CustomerSupport\Ticket\Model\TicketFactory;
use CustomerSupport\Ticket\Model\ResourceModel\Ticket as TicketResource;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    public const ADMIN_RESOURCE = 'CustomerSupport_Ticket::manage';

    private TicketFactory $ticketFactory;
    private TicketResource $ticketResource;
    private PageFactory $pageFactory;
    private RedirectFactory $redirectFactory;

    public function __construct(
        Action\Context $context,
        TicketFactory $ticketFactory,
        TicketResource $ticketResource,
        PageFactory $pageFactory,
        RedirectFactory $redirectFactory
    ) {
        $this->ticketFactory = $ticketFactory;
        $this->ticketResource = $ticketResource;
        $this->pageFactory = $pageFactory;
        $this->redirectFactory = $redirectFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $ticket = $this->ticketFactory->create();
        $this->ticketResource->load($ticket, (int)$this->getRequest()->getParam('id'));
        if (!$ticket->getId()) {
            $this->messageManager->addErrorMessage(__('The ticket no longer exists.'));
            return $this->redirectFactory->create()->setPath('*/*/');
        }

        $page = $this->pageFactory->create();
        $page->setActiveMenu('CustomerSupport_Ticket::tickets');
        $page->getConfig()->getTitle()->prepend(__('Ticket #%1', $ticket->getId()));
        return $page;
    }
}
