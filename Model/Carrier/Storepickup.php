<?php

namespace Excellence\Storepickup\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;

class Storepickup extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'storepickup';
    protected $check;
    protected $_cart;
    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Excellence\Storepickup\Block\Check $check,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        array $data = []
    ) {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->check = $check;
        $this->_cart = $cart;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * @return array
     */
    public function getAllowedMethods() {
        return ['storepickup' => $this->getConfigData('name')];
    }

    /**
     * @param RateRequest $request
     * @return bool|Result
     */
    public function collectRates(RateRequest $request) {
        if (!$this->getConfigFlag('active')) {
            return false;
        }
        $info = $this->check->getStorepickup();
        if (!empty($info)) {

            /** @var \Magento\Shipping\Model\Rate\Result $result */
            $result = $this->_rateResultFactory->create();

            /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
            $method = $this->_rateMethodFactory->create();

            $method->setCarrier('storepickup');
            $method->setCarrierTitle($this->getConfigData('title'));

            $method->setMethod('storepickup');
            $method->setMethodTitle($this->getConfigData('name'));

            /*you can fetch shipping price from different sources over some APIs, we used price from config.xml - xml node price*/
            $amount = $this->getConfigData('price');

            $handling_type = $this->_scopeConfig->getValue('carriers/storepickup/handling_type');
            $fee = $this->getConfigData('handling_fee');
            if ($handling_type == 1) {
                $items = $this->_cart->getQuote()->getAllItems();
                $price = 0;
                foreach ($items as $item) {
                    $price += $item->getPrice();
                }
                $perFee = $price * $fee;
                $amount_fix = $perFee / 100;
            } else {
                $amount_fix = $fee;
            }
            $method->setPrice($amount + $amount_fix);
            $method->setCost($amount + $amount_fix);

            $result->append($method);

            return $result;
        } else {
            return false;
        }
    }
}