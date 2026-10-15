<?php

namespace CustomerSupport\Ticket\Controller\Adminhtml\Ticket;

use CustomerSupport\Ticket\Model\TicketFactory;
use CustomerSupport\Ticket\Model\ResourceModel\Ticket as TicketResource;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;

class Delete extends Action implements HttpPostActionInterface
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
        $this->ticketResource->load($ticket, (int)$this->getRequest()->getParam('id'));
        if (!$ticket->getId()) {
            $this->messageManager->addErrorMessage(__('The ticket no longer exists.'));
            return $this->redirectFactory->create()->setPath('*/*/');
        }

        try {
            $this->ticketResource->delete($ticket);
            $this->messageManager->addSuccessMessage(__('The ticket has been deleted.'));
        } catch (\Exception $exception) {
            $this->messageManager->addExceptionMessage($exception, __('The ticket could not be deleted.'));
        }

        return $this->redirectFactory->create()->setPath('*/*/');
    }
}
