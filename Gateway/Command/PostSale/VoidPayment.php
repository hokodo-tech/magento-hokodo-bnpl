<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
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

class VoidPayment implements CommandInterface
{
    /**
     * @var PostSale
     */
    private PostSale $postSale;

    /**
     * @var DeferredPaymentsPostSaleActionInterfaceFactory
     */
    private DeferredPaymentsPostSaleActionInterfaceFactory $postSaleActionInterfaceFactory;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param DeferredPaymentsPostSaleActionInterfaceFactory $postSaleActionInterfaceFactory
     * @param PostSale                                       $postSale
     * @param LoggerInterface                                $logger
     */
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
     *
     * @throws ApiGatewayException
     */
    public function execute(array $commandSubject)
    {
        try {
            if (isset($commandSubject['payment'])) {
                $paymentDO = $commandSubject['payment'];
                /* @var OrderPaymentInterface $paymentInfo */
                $paymentInfo = $paymentDO->getPayment();
                $paymentInfo->setTransactionId($paymentInfo->getTransactionId() . '-' . time());
                if ($hokodoDeferredPaymentId = $paymentInfo->getAdditionalInformation()['hokodo_deferred_payment_id']) {
                    /** @var $postSaleAction DeferredPaymentsPostSaleActionInterface */
                    $postSaleAction = $this->postSaleActionInterfaceFactory->create();
                    $postSaleAction->setPaymentId($hokodoDeferredPaymentId);

                    return $this->postSale->voidRemaining($postSaleAction);
                }
            }
        } catch (\Exception $e) {
            $data = [
                'message' => 'Hokodo_BNPL: Error voiding the payment.',
                'error' => $e->getMessage(),
            ];
            $this->logger->error(__METHOD__, $data);
        }

        throw new ApiGatewayException(__('Void order payment error. See logs for details.'));
    }
}
