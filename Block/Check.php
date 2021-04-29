<?php

namespace Excellence\Storepickup\Block;

use \Excellence\Storebase\Model\Storebase;

use Magento\Store\Model\ScopeInterface;

class Check extends \Magento\Framework\View\Element\Template
{
    protected $_scopeConfigObject;

    /** @var Excellence\Storebase\Helper\Data\Data */
    protected $_dataHelper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Excellence\Storebase\Helper\Data $dataHelper
    ) {
        $this->registry = $registry;
        $this->_dataHelper = $dataHelper;
        $this->_scopeConfigObject = $context->getscopeConfig();
        $this->pageConfig = $context->getPageConfig();
        parent::__construct($context);
    }

    protected function _prepareLayout() {
        if (!empty($this->_scopeConfigObject->getValue(Storebase::GOOGLE_SEO.'/metaTitle', ScopeInterface::SCOPE_STORE))) {
            $this->pageConfig->getTitle()->set($this->_scopeConfigObject->getValue(Storebase::GOOGLE_SEO.'/metaTitle', ScopeInterface::SCOPE_STORE));
        }
        if (!empty($this->_scopeConfigObject->getValue(Storebase::GOOGLE_SEO.'/metaKeywords', ScopeInterface::SCOPE_STORE))) {
            $this->pageConfig->setKeywords($this->_scopeConfigObject->getValue(Storebase::GOOGLE_SEO.'/metaKeywords', ScopeInterface::SCOPE_STORE));
        }
        if (!empty($this->_scopeConfigObject->getValue(Storebase::GOOGLE_SEO.'/metaDescription', ScopeInterface::SCOPE_STORE))) {
            $this->pageConfig->setDescription($this->_scopeConfigObject->getValue(Storebase::GOOGLE_SEO.'/metaDescription', ScopeInterface::SCOPE_STORE));
        }
        parent::_prepareLayout();
    }

    public function getValue() {
        $position = $this->registry->registry('info_val');
        return $position;
    }

    // Function to get the details of the storepickup in ADMIN(order section) selected while checkout.
    public function getStorepickup() {
        // get Store Id
        $storeViewId = $this->_storeManager->getStore()->getId();

        // filter the storebase collection
        $storeCollection = $this->_dataHelper->getAvailableStores();

        // declare an empty array
        $storeCollectionData = array();

        // run a loop to get the storepickup details of selected one
        foreach ($storeCollection as $store) {
            if($store['is_storepickup'] == 1) {
                // explode the detail in the form of array
                $storeViews = explode(',', $store['store_view']);

                // check if the storepickup selected exist in the collection
                if (in_array($storeViewId, $storeViews) || in_array(\Magento\Backend\Block\Widget\Grid\Column\Filter\Store::ALL_STORE_VIEWS, $storeViews)) {
                    // Get country name from country code
                    $store['country'] = $this->_dataHelper->getCountryname($store['country']);
                    // Assign data to array
                    $storeCollectionData[] = $store;
                }
            }
        }
        // return the storepickup details
        return $storeCollectionData;
    }
}
