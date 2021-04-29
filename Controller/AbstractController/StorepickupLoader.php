<?php

namespace Excellence\Storepickup\Controller\AbstractController;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Registry;

class StorepickupLoader implements StorepickupLoaderInterface
{
    /**
     * @var \Excellence\Storebase\Model\StorebaseFactory
     */
    protected $storebaseFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $url;

    /**
     * @param \Excellence\Storepickup\Model\StorebaseFactory $storebaseFactory
     * @param OrderViewAuthorizationInterface $orderAuthorization
     * @param Registry $registry
     * @param \Magento\Framework\UrlInterface $url
     */
    public function __construct(
        \Excellence\Storebase\Model\StorebaseFactory $storebaseFactory,
        Registry $registry,
        \Magento\Framework\UrlInterface $url
    ) {
        $this->storebaseFactory = $storebaseFactory;
        $this->registry = $registry;
        $this->url = $url;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return bool
     */
    public function load(RequestInterface $request, ResponseInterface $response) {
        $id = (int) $request->getParam('id');
        if (!$id) {
            $request->initForward();
            $request->setActionName('noroute');
            $request->setDispatched(false);
            return false;
        }

        $storepickup = $this->storebaseFactory->create()->load($id);
        $this->registry->register('current_storepickup', $storepickup);
        return true;
    }
}
