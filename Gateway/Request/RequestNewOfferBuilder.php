<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Request;

use Hokodo\BNPL\Gateway\PaymentOfferSubjectReader;
use Hokodo\BNPL\Model\ServiceUrl;
use Magento\Framework\UrlInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Hokodo\BNPL\Gateway\Request\RequestNewOfferBuilder.
 */
class RequestNewOfferBuilder implements BuilderInterface
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var ServiceUrl
     */
    private $serviceUrl;

    /**
     * @var PaymentOfferSubjectReader
     */
    private $subjectReader;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * A constructor.
     *
     * @param UrlInterface              $urlBuilder
     * @param ServiceUrl                $serviceUrl
     * @param PaymentOfferSubjectReader $subjectReader
     * @param StoreManagerInterface     $storeManager
     */
    public function __construct(
        UrlInterface $urlBuilder,
        ServiceUrl $serviceUrl,
        PaymentOfferSubjectReader $subjectReader,
        StoreManagerInterface $storeManager
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->serviceUrl = $serviceUrl;
        $this->subjectReader = $subjectReader;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Payment\Gateway\Request\BuilderInterface::build()
     */
    public function build(array $buildSubject)
    {
        return [
            'body' => $this->createPaymentNewOfferRequest($buildSubject),
        ];
    }

    /**
     * A function creates payment new offer request.
     *
     * @param array $buildSubject
     *
     * @return array
     */
    private function createPaymentNewOfferRequest(array $buildSubject)
    {
        $orderId = $this->readOrderId($buildSubject);
        $request = [
            'order' => $orderId,
            'urls' => [
                'success' => $this->urlBuilder->getUrl('hokodo_bnpl/onAuthorization', [
                    'orderApiId' => $orderId,
                    'quoteId' => $this->subjectReader->readFieldValue('quote_id', $buildSubject),
                    'customerId' => $this->subjectReader->readFieldValue('customer_id', $buildSubject),
                ]),
                'failure' => $this->urlBuilder->getUrl('checkout'),
                'cancel' => $this->urlBuilder->getUrl('checkout'),
                'notification' => $this->serviceUrl->getUrl('deferredpayment/ipn'),
                'merchant_terms' => '',
            ],
        ];

        if ($this->storeManager->getStore()->isUseStoreInUrl()) {
            $request['urls']['notification'] = $this->serviceUrl->getUrlWithoutStoreCode('deferredpayment/ipn');
        }

        return $request;
    }

    /**
     * A function reads order id.
     *
     * @param array $buildSubject
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    private function readOrderId(array $buildSubject)
    {
        return $this->subjectReader->readOrderId($buildSubject);
    }
}
