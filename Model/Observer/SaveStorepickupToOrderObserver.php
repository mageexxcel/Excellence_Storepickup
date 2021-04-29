<?php

namespace Excellence\Storepickup\Model\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class SaveStorepickupToOrderObserver implements ObserverInterface
{
    protected $quoteRepository;

    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository
    ) {
        $this->_quoteRepository = $quoteRepository;
    }

    public function execute(EventObserver $observer) {
        $order = $observer->getOrder();
        $quote = $this->_quoteRepository->get($order->getQuoteId());
        $order->setStorebase($quote->getStorebase());
        $order->save();
        return $this;
    }
}