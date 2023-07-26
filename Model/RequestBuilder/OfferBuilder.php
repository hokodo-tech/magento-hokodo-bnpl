<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\RequestBuilder;

use Hokodo\BNPL\Api\Data\Gateway\CreateOfferRequestInterface;
use Hokodo\BNPL\Api\Data\Gateway\CreateOfferRequestInterfaceFactory;
use Hokodo\BNPL\Api\Data\Gateway\OfferUrlsInterface;
use Hokodo\BNPL\Api\Data\Gateway\OfferUrlsInterfaceFactory;
use Hokodo\BNPL\Model\ServiceUrl;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\UrlInterface;

class OfferBuilder
{
    /**
     * @var OfferUrlsInterfaceFactory
     */
    private OfferUrlsInterfaceFactory $offerUrlsFactory;

    /**
     * @var ResolverInterface
     */
    private ResolverInterface $localeResolver;

    /**
     * @var CreateOfferRequestInterfaceFactory
     */
    private CreateOfferRequestInterfaceFactory $createOfferRequestFactory;

    /**
     * @var ServiceUrl
     */
    private ServiceUrl $serviceUrl;

    /**
     * @var UrlInterface
     */
    private UrlInterface $url;

    /**
     * @param OfferUrlsInterfaceFactory          $offerUrlsFactory
     * @param CreateOfferRequestInterfaceFactory $createOfferRequestFactory
     * @param ResolverInterface                  $localeResolver
     * @param ServiceUrl                         $serviceUrl
     * @param UrlInterface                       $url
     */
    public function __construct(
        OfferUrlsInterfaceFactory $offerUrlsFactory,
        CreateOfferRequestInterfaceFactory $createOfferRequestFactory,
        ResolverInterface $localeResolver,
        ServiceUrl $serviceUrl,
        UrlInterface $url
    ) {
        $this->offerUrlsFactory = $offerUrlsFactory;
        $this->localeResolver = $localeResolver;
        $this->createOfferRequestFactory = $createOfferRequestFactory;
        $this->serviceUrl = $serviceUrl;
        $this->url = $url;
    }

    /**
     * Builds offer request object.
     *
     * @param string $orderId
     *
     * @return CreateOfferRequestInterface
     */
    public function build(string $orderId): CreateOfferRequestInterface
    {
        $urls = $this->offerUrlsFactory->create();
        /* @var $urls OfferUrlsInterface */
        $urls->setSuccessUrl($this->url->getUrl())
            ->setCancelUrl($this->url->getUrl())
            ->setFailureUrl($this->url->getUrl())
            ->setMerchantTermsUrl($this->url->getUrl())
            ->setNotificationUrl($this->serviceUrl->getUrl('deferredpayment/update'));

        $offerRequest = $this->createOfferRequestFactory->create();
        /* @var $offerRequest CreateOfferRequestInterface */
        $offerRequest
            ->setOrder($orderId)
            ->setUrls($urls)
            ->setLocale($this->localeResolver->getLocale())
            ->setMetadata([]);

        return $offerRequest;
    }
}
