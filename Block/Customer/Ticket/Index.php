<?php

namespace CustomerSupport\Ticket\Block\Customer\Ticket;

use CustomerSupport\Ticket\Model\ResourceModel\Ticket\CollectionFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Template;

class Index extends Template
{
    private CollectionFactory $collectionFactory;
    private CustomerSession $customerSession;

    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        CustomerSession $customerSession,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    public function getTickets()
    {
        return $this->collectionFactory->create()
            ->addFieldToFilter('customer_id', (int)$this->customerSession->getCustomerId())
            ->setOrder('created_at', 'DESC');
    }
}
