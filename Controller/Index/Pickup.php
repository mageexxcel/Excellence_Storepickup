<?php

namespace Excellence\Storepickup\Controller\Index;

class Pickup extends \Magento\Framework\App\Action\Action
{    
    protected $quoteRepository;

    protected $_cart;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Quote\Model\QuoteRepositoryFactory $quoteRepository,
        \Magento\Checkout\Model\Cart $cart
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->_cart = $cart;
        parent::__construct($context);
    }

    /**
     * Default Storepickup Index page
     *
     * @return void
     */
    public function execute() {
        $cartId = $this->_cart->getQuote()->getId();
        $quoteRepository = $this->quoteRepository->create();
        $storebase = $this->getRequest()->getPostValue('storepickup');
        $quote = $quoteRepository->getActive($cartId);
        $quote->setStorebase($storebase);
        $quote->save();
    }
}
