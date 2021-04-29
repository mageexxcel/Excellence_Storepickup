<?php

namespace Excellence\Storepickup\Block;

/**
 * Storepickup content block
 */
class Storepickup extends \Magento\Framework\View\Element\Template
{
    /**
     * Storepickup collection
     *
     * @var Excellence\Storepickup\Model\ResourceModel\Storepickup\Collection
     */
    protected $_storepickupCollection = null;

    /**
     * Storepickup factory
     *
     * @var \Excellence\Storepickup\Model\StorebaseCollectionFactory
     */
    protected $_storebaseCollectionFactory;

    /** @var Excellence\Storebase\Helper\Data\Data */
    protected $_dataHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Excellence\Storepickup\Model\ResourceModel\Storepickup\CollectionFactory $storebaseCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Excellence\Storebase\Model\ResourceModel\Storebase\Collection $storebaseCollectionFactory,
        \Excellence\Storebase\Helper\Data $dataHelper,
        array $data = []
    ) {
        $this->_storebaseCollectionFactory = $storebaseCollectionFactory;
        $this->_dataHelper = $dataHelper;
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * Retrieve storepickup collection
     *
     * @return Excellence\Storepickup\Model\ResourceModel\Storepickup\Collection
     */
    protected function _getCollection() {
        $collection = $this->_storebaseCollectionFactory->create();
        return $collection;
    }

    /**
     * Retrieve prepared storepickup collection
     *
     * @return Excellence\Storepickup\Model\ResourceModel\Storepickup\Collection
     */
    public function getCollection() {
        if (is_null($this->_storepickupCollection)) {
            $this->_storepickupCollection = $this->_getCollection();
            $this->_storepickupCollection->setCurPage($this->getCurrentPage());
            $this->_storepickupCollection->setPageSize($this->_dataHelper->getStorepickupPerPage());
            $this->_storepickupCollection->setOrder('published_at', 'asc');
        }

        return $this->_storepickupCollection;
    }

    /**
     * Fetch the current page for the storepickup list
     *
     * @return int
     */
    public function getCurrentPage() {
        return $this->getData('current_page') ? $this->getData('current_page') : 1;
    }

    /**
     * Return URL to item's view page
     *
     * @param Excellence\Storepickup\Model\Storepickup $storepickupItem
     * @return string
     */
    public function getItemUrl($storepickupItem) {
        return $this->getUrl('*/*/view', array('id' => $storepickupItem->getId()));
    }

    /**
     * Return URL for resized Storepickup Item image
     *
     * @param Excellence\Storepickup\Model\Storepickup $item
     * @param integer $width
     * @return string|false
     */
    public function getImageUrl($item, $width) {
        return $this->_dataHelper->resize($item, $width);
    }

    /**
     * Get a pager
     *
     * @return string|null
     */
    public function getPager() {
        $pager = $this->getChildBlock('storepickup_list_pager');
        if ($pager instanceof \Magento\Framework\Object) {
            $storepickupPerPage = $this->_dataHelper->getStorepickupPerPage();

            $pager->setAvailableLimit([$storepickupPerPage => $storepickupPerPage]);
            $pager->setTotalNum($this->getCollection()->getSize());
            $pager->setCollection($this->getCollection());
            $pager->setShowPerPage(TRUE);
            $pager->setFrameLength(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            )->setJump(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame_skip',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            );

            return $pager->toHtml();
        }

        return NULL;
    }
}
