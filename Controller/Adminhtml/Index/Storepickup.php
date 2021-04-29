<?php

namespace Excellence\StorePickup\Controller\Adminhtml\Index;

class StorePickup extends \Magento\Backend\App\Action
{
    protected $request;
    protected $resultRedirectFactory = false;
    protected $_storebaseFactory;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\Http $request,
        \Excellence\Storebase\Model\StorebaseFactory $storebaseFactory
    ) {
        parent::__construct($context);
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        $this->_storebaseFactory = $storebaseFactory;
        $this->request = $request;
    }

    public function execute() {
        $post = $this->request->getParams();
        if(isset($post)){
            $model = $this->_storebaseFactory->create();
            $model->load($post['storebase_id']);
            $model->setIsStorepickup($post['is_storepickup']);
            if($model->save()){
                if ($post['is_storepickup'] == 1) {
                    $this->messageManager->addSuccess(__("Store '%1' has been enabled for StorePickup.",$model->getStoreName()));
                } else {
                    $this->messageManager->addSuccess(__("Store '%1' has been disabled for StorePickup.",$model->getStoreName()));
                }
            }
            else{
                $this->messageManager->addError(__('Some error occured while saving the Store. Please try again.'));
            }
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('storebase/index/index');
        return $resultRedirect;
    }
}