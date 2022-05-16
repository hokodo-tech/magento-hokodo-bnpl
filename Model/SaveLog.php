<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Model;

use Exception;
use Hokodo\BNPL\Service\LogObfuscatorService;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class SaveLog
{
    /**
     * PaymentLog Factory variable.
     *
     * @var PaymentLogFactory
     */
    protected $paymentLogFactory;

    /**
     * PaymentLogRepository variable.
     *
     * @var PaymentLogRepository
     */
    protected $paymentLogRepository;

    /**
     * LoggerInterface variable.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * State variable.
     *
     * @var State
     */
    protected $state;

    /**
     * LogObfuscatorService variable.
     *
     * @var LogObfuscatorService
     */
    protected $logObfuscatorService;

    /**
     * Processor constructor.
     *
     * @param PaymentLogFactory    $paymentLogFactory
     * @param PaymentLogRepository $paymentLogRepository
     * @param LoggerInterface      $logger
     * @param State                $state
     * @param LogObfuscatorService $logObfuscatorService
     */
    public function __construct(
        PaymentLogFactory $paymentLogFactory,
        PaymentLogRepository $paymentLogRepository,
        LoggerInterface $logger,
        State $state,
        LogObfuscatorService $logObfuscatorService
    ) {
        $this->paymentLogFactory = $paymentLogFactory;
        $this->paymentLogRepository = $paymentLogRepository;
        $this->logger = $logger;
        $this->state = $state;
        $this->logObfuscatorService = $logObfuscatorService;
    }

    /**
     * State.
     *
     * @return State
     */
    public function getAppState()
    {
        return $this->state;
    }

    /**
     * Save log.
     *
     * @param array $data
     *
     * @throws LocalizedException
     * @throws Exception
     */
    public function execute($data)
    {
        $saved = false;
        if (!empty($data)) {
            try {
                if (!$this->getAppState()->getAreaCode()) {
                    $this->getAppState()->setAreaCode(Area::AREA_GLOBAL);
                }
            } catch (\Exception $e) {
                $this->getAppState()->setAreaCode(Area::AREA_GLOBAL);
            }
            $paymentLogModel = $this->paymentLogFactory->create();
            try {
                if (is_array($data['payment_log_content'])) {
                    $data['payment_log_content'] = json_encode($data['payment_log_content']);
                    $data['payment_log_content'] = $this->logObfuscatorService->execute($data['payment_log_content']);
                }
                $paymentLogModel->setData($data);
                $this->paymentLogRepository->save($paymentLogModel);

                if ($paymentLogModel->getId()) {
                    $saved = true;
                }
            } catch (LocalizedException|Exception $ex) {
                $this->logger->debug($ex->getMessage());
            }
        }
        return $saved;
    }
}
