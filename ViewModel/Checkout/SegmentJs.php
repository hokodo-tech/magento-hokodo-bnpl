<?php

declare(strict_types=1);

namespace Hokodo\BNPL\ViewModel\Checkout;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class SegmentJs implements ArgumentInterface
{
    public const HOKODO_PAYMENT_ENV = 'payment/hokodo_bnpl/environment';

    private const SEGMENT_SANDBOX_KEY = '1sTpGIGYD3zs1NnZ7VrP6AwqCsSnD8EA';

    private const SEGMENT_PRODUCTION_KEY = 'Ym0AF6w9WMfLO4FP1SUek96XSHw4fr99';

    /**
     * @var ScopeConfigInterface
     */
    private $config;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * SegmentJs constructor.
     *
     * @param ScopeConfigInterface  $config
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ScopeConfigInterface $config,
        StoreManagerInterface $storeManager
    ) {
        $this->config = $config;
        $this->storeManager = $storeManager;
    }

    /**
     * Returns segment.js api key.
     *
     * @return string|null
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getKey()
    {
        switch ($this->config->getValue(
            self::HOKODO_PAYMENT_ENV,
            ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        )) {
            case 'dev':
            case 'sandbox':
                return self::SEGMENT_SANDBOX_KEY;
            case 'production':
                return self::SEGMENT_PRODUCTION_KEY;
            default:
                return null;
        }
    }
}
