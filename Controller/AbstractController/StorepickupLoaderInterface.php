<?php

namespace Excellence\Storepickup\Controller\AbstractController;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;

interface StorepickupLoaderInterface
{
    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return \Excellence\Storepickup\Model\Storepickup
     */
    public function load(RequestInterface $request, ResponseInterface $response);
}
