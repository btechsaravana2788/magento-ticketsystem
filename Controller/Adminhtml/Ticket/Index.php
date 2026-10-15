<?php

namespace CustomerSupport\Ticket\Controller\Adminhtml\Ticket;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    public const ADMIN_RESOURCE = 'CustomerSupport_Ticket::manage';

    private PageFactory $pageFactory;

    public function __construct(Action\Context $context, PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $page = $this->pageFactory->create();
        $page->setActiveMenu('CustomerSupport_Ticket::tickets');
        $page->getConfig()->getTitle()->prepend(__('Support Tickets'));
        return $page;
    }
}
