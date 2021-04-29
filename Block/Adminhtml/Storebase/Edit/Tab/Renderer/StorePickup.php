<?php

namespace Excellence\StorePickup\Block\Adminhtml\Storebase\Edit\Tab\Renderer;

use Magento\Framework\DataObject;

class StorePickup extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $_dataHelper;
    protected $_backendHelper;
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Excellence\Storebase\Helper\Data $dataHelper,
        \Magento\Backend\Helper\Data $backendHelper
    ) {
        $this->_dataHelper = $dataHelper;
        $this->_backendHelper = $backendHelper;
        parent::__construct($context);
    }
    public function render(DataObject $row) {
        $storebaseId = $row->getId();
        $storeData = $this->_dataHelper->getStoresByStoreId($storebaseId);
        if ($storeData['is_storepickup'] == 0) {
            $storepickup = 1;
        } else {
            $storepickup = 0;
        }
        $storebaseUrl =  $this->_backendHelper->getUrl('storepickup/index/storepickup', ['storebase_id' => $storebaseId, 'is_storepickup' => $storepickup]);
        if (!empty($storebaseId)) {
            if ($storeData['is_storepickup'] == 0) {
                $link = __('Enable');
                $label = __('No') ;
                return $label.'<br />(<a href="' . $storebaseUrl . '">' . $link . '</a>)';
            } else {
                $link = __('Disable');
                $label = __('Yes');
                return $label.'<br />(<a href="' . $storebaseUrl . '">' . $link . '</a>)';
            }
        } else {
            return false;
        }
    }
}
