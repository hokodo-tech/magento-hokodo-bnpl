<?php

/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Controller\Adminhtml\Company;

use Hokodo\BNPL\Api\Data\Company\CreditLimitInterface;
use Hokodo\BNPL\Api\Data\HokodoEntityInterface;
use Hokodo\BNPL\Model\HokodoCompanyProvider;
use Magento\Directory\Model\Currency;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Psr\Log\LoggerInterface;

class Credit implements HttpPostActionInterface
{
    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var HokodoEntityInterface|null
     */
    private ?HokodoEntityInterface $hokodoEntity = null;

    /**
     * @var ResultFactory
     */
    private ResultFactory $resultFactory;

    /**
     * @var HokodoCompanyProvider
     */
    private HokodoCompanyProvider $hokodoCompanyProvider;

    /**
     * @var PriceCurrencyInterface
     */
    private PriceCurrencyInterface $priceCurrency;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var CurrencyFactory
     */
    private CurrencyFactory $currencyFactory;

    /**
     * @param RequestInterface       $request
     * @param ResultFactory          $resultFactory
     * @param PriceCurrencyInterface $priceCurrency
     * @param CurrencyFactory        $currencyFactory
     * @param HokodoCompanyProvider  $hokodoCompanyProvider
     * @param LoggerInterface        $logger
     */
    public function __construct(
        RequestInterface $request,
        ResultFactory $resultFactory,
        PriceCurrencyInterface $priceCurrency,
        CurrencyFactory $currencyFactory,
        HokodoCompanyProvider $hokodoCompanyProvider,
        LoggerInterface $logger
    ) {
        $this->request = $request;
        $this->resultFactory = $resultFactory;
        $this->hokodoCompanyProvider = $hokodoCompanyProvider;
        $this->priceCurrency = $priceCurrency;
        $this->logger = $logger;
        $this->currencyFactory = $currencyFactory;
    }

    /**
     * Execute action based on request and return result.
     *
     * @return ResultInterface|ResponseInterface
     *
     * @throws NotFoundException
     */
    public function execute()
    {
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $creditLimit = ['is_eligible' => false];
        try {
            if ($this->isEligible()) {
                $creditLimit = [
                    'is_eligible' => $this->isEligible(),
                    'amount' => $this->getAmount(),
                    'amount_in_use' => $this->getAmountInUse(),
                    'amount_available' => $this->getAmountAvailable(),
                    'rejection_reason' => $this->getRejectionReason(),
                ];
            }
        } catch (\Exception $e) {
            $this->logger->critical(
                __METHOD__,
                [
                    'message' => 'Hokodo_BNPL: get credit limit failed with error.',
                    'error' => $e->getMessage(),
                ]
            );
        }
        $result->setData($creditLimit);

        return $result;
    }

    /**
     * Is company eligible.
     *
     * @return bool
     */
    public function isEligible(): bool
    {
        return (bool) $this->getCredit();
    }

    /**
     * Get credit amount.
     *
     * @return string
     */
    public function getAmount(): string
    {
        return $this->getFormattedPrice($this->getCredit()->getAmount() / 100, 0);
    }

    /**
     * Get credit amount in use.
     *
     * @return string
     */
    public function getAmountInUse(): string
    {
        return $this->getFormattedPrice($this->getCredit()->getAmountInUse() / 100);
    }

    /**
     * Get credit limit available.
     *
     * @return string
     */
    public function getAmountAvailable(): string
    {
        return $this->getFormattedPrice($this->getCredit()->getAmountAvailable() / 100);
    }

    /**
     * Get Reject Reason.
     *
     * @return string|null
     */
    public function getRejectionReason(): ?string
    {
        return $this->getCredit()->getRejectionReason();
    }

    /**
     * Get credit object.
     *
     * @return CreditLimitInterface|null
     */
    public function getCredit(): ?CreditLimitInterface
    {
        return $this->getHokodoEntity() ? $this->getHokodoEntity()->getCreditLimit() : null;
    }

    /**
     * Get hokodo entity model.
     *
     * @return HokodoEntityInterface|null
     */
    public function getHokodoEntity(): ?HokodoEntityInterface
    {
        $entityId = $this->request->getParam('id');
        if ($entityId && !$this->hokodoEntity) {
            $this->hokodoEntity = $this->hokodoCompanyProvider->getEntityRepository()->getByEntityId((int) $entityId);
        }

        return $this->hokodoEntity;
    }

    /**
     * Get formatted price for components.
     *
     * @param float    $amount
     * @param int|null $precision
     *
     * @return string
     */
    private function getFormattedPrice(float $amount, int $precision = null): string
    {
        if ($currency = $this->getCurrency($this->getCredit()->getCurrency())) {
            return $currency->formatPrecision($amount, $precision ?? 2, [], false);
        }

        return $this->priceCurrency->format(
            $amount,
            false,
            $precision ?? 2,
            null,
            $this->getCredit()->getCurrency()
        );
    }

    /**
     * Get currency.
     *
     * @param string $code
     *
     * @return Currency|null
     */
    public function getCurrency(string $code): ?Currency
    {
        $currency = $this->currencyFactory->create();
        try {
            return $currency->load($code);
        } catch (NoSuchEntityException $e) { // @codingStandardsIgnoreLine
        }
        return null;
    }
}
