<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Command\PostSale;

use Hokodo\BNPL\Api\Data\Gateway\DeferredPaymentsPostSaleActionInterface;
use Hokodo\BNPL\Api\Data\Gateway\DeferredPaymentsPostSaleActionInterfaceFactory;
use Hokodo\BNPL\Exception\ApiGatewayException;
use Hokodo\BNPL\Gateway\Service\PostSale;
use Magento\Payment\Gateway\CommandInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Psr\Log\LoggerInterface;

class CapturePayment implements CommandInterface
{
    private PostSale $postSale;
    private DeferredPaymentsPostSaleActionInterfaceFactory $postSaleActionInterfaceFactory;
    private LoggerInterface $logger;

    public function __construct(
        DeferredPaymentsPostSaleActionInterfaceFactory $postSaleActionInterfaceFactory,
        PostSale $postSale,
        LoggerInterface $logger
    ) {
        $this->postSale = $postSale;
        $this->postSaleActionInterfaceFactory = $postSaleActionInterfaceFactory;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function execute(array $commandSubject)
    {
        try {
            if (($paymentDO = $commandSubject['payment'] ?? null) && ($amount = $commandSubject['amount'] ?? null)) {
                /* @var OrderPaymentInterface $paymentInfo */
                $paymentInfo = $paymentDO->getPayment();
                if ($hokodoDeferredPaymentId = $paymentInfo->getAdditionalInformation()['hokodo_deferred_payment_id']) {
                    /** @var $postSaleAction DeferredPaymentsPostSaleActionInterface */
                    $postSaleAction = $this->postSaleActionInterfaceFactory->create();
                    $postSaleAction->setPaymentId($hokodoDeferredPaymentId)->setAmount((int) ($amount * 100));

                    $result =  $this->postSale->capture($postSaleAction);
                    return $result;
                }
            }
        } catch (\Exception $e) {
            $this->logger->error(
                __('Hokodo_BNPL: Error voiding the payment - %1', $e->getMessage())
            );
        }

        throw new ApiGatewayException(__('Capture order payment error. See logs for details.'));
    }
}
