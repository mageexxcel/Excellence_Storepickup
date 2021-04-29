<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Excellence\Storepickup\Model\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class AddHtmlToOrderShippingViewObserver implements ObserverInterface
{
    protected $template;

    protected $_dataHelper;

    public function __construct(
        \Excellence\Storebase\Model\StorebaseFactory $storebaseFactory,
        \Excellence\Storebase\Helper\Data $dataHelper,
        \Magento\Framework\View\Element\Template $template
    ) {
        $this->storebaseFactory = $storebaseFactory;
        $this->_dataHelper = $dataHelper;
        $this->_template = $template;
    }

    public function execute(EventObserver $observer) {
        if ($observer->getElementName() == 'order_shipping_view') {
            $orderShippingViewBlock = $observer->getLayout()->getBlock($observer->getElementName());
            $order = $orderShippingViewBlock->getOrder();
            $storePickupBlock = $this->_template;
            $model = $this->storebaseFactory->create()->load($order->getStorebase());
            $data = null;
            if (isset($model['number'])) {
                if ($model['street_two']) {
                    $data = '<br/>'.$model['store_name'] . ',<br />' . $model['street_one'] . ', ' . $model['street_two'] . ',<br />' . $model['city'] . ',<br />' . $model['region_id'] . ' - ' . $model['zipcode'] . ',<br />' . $this->_dataHelper->getCountryname($model['country']) . ',<br />' . 'T:' . $model['number'];
                } else {
                    $data = '<br/>'.$model['store_name'] . ',<br />' . $model['street_one'] . ',<br />' . $model['city'] . ',<br />' . $model['region_id'] . ' - ' . $model['zipcode'] . ',<br />' . $this->_dataHelper->getCountryname($model['country']) . ',<br />' . 'T:' . $model['number'];
                }
            }
            $storePickupBlock->setStorepickup($data);
            $storePickupBlock->setTemplate('Excellence_Storepickup::order_info_shipping_info.phtml');
            $html = $observer->getTransport()->getOutput() . $storePickupBlock->toHtml();

            $observer->getTransport()->setOutput($html);
        }
        if ($observer->getElementName() == 'form') {
            $orderShippingViewBlock = $observer->getLayout()->getBlock($observer->getElementName());
            $order = $orderShippingViewBlock->getOrder();
            $storePickupBlock = $this->_template;
            $model = $this->storebaseFactory->create()->load($order->getStorebase());
            $data = null;
            if (isset($model['number'])) {
                if ($model['street_two']) {
                    $data = '<br/>'.$model['store_name'] . ',<br />' . $model['street_one'] . ', ' . $model['street_two'] . ',<br />' . $model['city'] . ',<br />' . $model['region_id'] . ' - ' . $model['zipcode'] . ',<br />' . $this->_dataHelper->getCountryname($model['country']) . ',<br />' . 'T:' . $model['number'];
                } else {
                    $data = '<br/>'.$model['store_name'] . ',<br />' . $model['street_one'] . ',<br />' . $model['city'] . ',<br />' . $model['region_id'] . ' - ' . $model['zipcode'] . ',<br />' . $this->_dataHelper->getCountryname($model['country']) . ',<br />' . 'T:' . $model['number'];
                }
            }
            $storePickupBlock->setStorepickup($data);
            $storePickupBlock->setTemplate('Excellence_Storepickup::order_info_shipping_info.phtml');
            $html = $observer->getTransport()->getOutput() . $storePickupBlock->toHtml();

            $observer->getTransport()->setOutput($html);
        }
    }
}