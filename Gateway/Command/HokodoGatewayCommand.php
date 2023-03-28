<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Command;

use Hokodo\BNPL\Service\ArrayToStringService;
use Magento\Framework\Exception\LocalizedException;
use Magento\Payment\Gateway\Command\CommandException;
use Magento\Payment\Gateway\Command\ResultInterfaceFactory;
use Magento\Payment\Gateway\CommandInterface;
use Magento\Payment\Gateway\ErrorMapper\ErrorMessageMapperInterface;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferFactoryInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Payment\Gateway\Validator\ResultInterface;
use Magento\Payment\Gateway\Validator\ValidatorInterface;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class Hokodo\BNPL\Gateway\Command\HokodoGatewayCommand.
 */
class HokodoGatewayCommand implements CommandInterface
{
    /**
     * @var ArrayToStringService
     */
    private ArrayToStringService $arrayToStringService;

    /**
     * @var BuilderInterface
     */
    private BuilderInterface $requestBuilder;

    /**
     * @var TransferFactoryInterface
     */
    private TransferFactoryInterface $transferFactory;

    /**
     * @var ClientInterface
     */
    private ClientInterface $client;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @var ResultInterfaceFactory
     */
    private ResultInterfaceFactory $resultFactory;

    /**
     * @var HandlerInterface
     */
    private ?HandlerInterface $handler;

    /**
     * @var ValidatorInterface
     */
    private ?ValidatorInterface $validator;

    /**
     * @var ErrorMessageMapperInterface
     */
    private ?ErrorMessageMapperInterface $errorMessageMapper;

    /**
     * @param ArrayToStringService        $arrayToStringService
     * @param BuilderInterface            $requestBuilder
     * @param TransferFactoryInterface    $transferFactory
     * @param ClientInterface             $client
     * @param Logger                      $logger
     * @param ResultInterfaceFactory      $resultFactory
     * @param HandlerInterface            $handler
     * @param ValidatorInterface          $validator
     * @param ErrorMessageMapperInterface $errorMessageMapper
     */
    public function __construct(
        ArrayToStringService $arrayToStringService,
        BuilderInterface $requestBuilder,
        TransferFactoryInterface $transferFactory,
        ClientInterface $client,
        Logger $logger,
        ResultInterfaceFactory $resultFactory,
        HandlerInterface $handler = null,
        ValidatorInterface $validator = null,
        ErrorMessageMapperInterface $errorMessageMapper = null
    ) {
        $this->arrayToStringService = $arrayToStringService;
        $this->requestBuilder = $requestBuilder;
        $this->transferFactory = $transferFactory;
        $this->client = $client;
        $this->logger = $logger;
        $this->resultFactory = $resultFactory;
        $this->handler = $handler;
        $this->validator = $validator;
        $this->errorMessageMapper = $errorMessageMapper;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Payment\Gateway\CommandInterface::execute()
     */
    public function execute(array $commandSubject)
    {
        $transferO = $this->transferFactory->create(
            $this->requestBuilder->build($commandSubject)
        );
        $response = $this->client->placeRequest($transferO);
        if ($this->validator !== null) {
            /**
             * @var ResultInterface $result
             */
            $result = $this->validator->validate(
                array_merge($commandSubject, ['response' => $response])
            );
            if (!$result->isValid()) {
                $this->processErrors($result);
            }
        }
        if ($this->handler) {
            $this->handler->handle(
                $commandSubject,
                $response
            );
        }
        return $this->resultFactory->create(['result' => $response]);
    }

    /**
     * A function that make process errors.
     *
     * @param ResultInterface $result
     *
     * @throws CommandException|LocalizedException
     *
     * @phpcs:disable Generic.Metrics.NestingLevel
     */
    private function processErrors(ResultInterface $result)
    {
        $messages = [];
        $errorsSource = array_merge($result->getErrorCodes(), $result->getFailsDescription());
        foreach ($errorsSource as $errorCodeOrMessage) {
            if (is_array($errorCodeOrMessage)) {
                foreach ($errorCodeOrMessage as $key => $value) {
                    if ($this->errorMessageMapper !== null) {
                        $mapped = (string) $this->errorMessageMapper->getMessage($key);
                        if (empty($mapped)) {
                            if (!is_array($value)) {
                                $mapped = (string) $this->errorMessageMapper->getMessage($value);
                            } else {
                                if (isset($value[0])) {
                                    $mapped = (string) $this->errorMessageMapper->getMessage(implode(', ', $value));
                                } else {
                                    $errorStr = $this->arrayToStringService->errorArrayToString($value);
                                    $mapped = (string) $this->errorMessageMapper->getMessage($errorStr);
                                }
                            }
                        }
                        if (!empty($mapped)) {
                            $messages[] = $mapped;
                            $errorCodeOrMessage = $mapped;
                        } else {
                            $messages[] = $value;
                            $errorCodeOrMessage = $value;
                        }
                    }
                }
            } else {
                $errorCodeOrMessage = (string) $errorCodeOrMessage;

                if ($this->errorMessageMapper !== null) {
                    $mapped = (string) $this->errorMessageMapper->getMessage($errorCodeOrMessage);
                    if (!empty($mapped)) {
                        $messages[] = $mapped;
                        $errorCodeOrMessage = $mapped;
                    }
                }
            }
        }
        $data = [
            'payment_log_content' => $messages,
            'action_title' => 'Payment Error',
            'status' => 0,
        ];
        $this->logger->error(__METHOD__, $data);
        $messagesString = $this->arrayToStringService->errorArrayToString($messages);

        throw new CommandException(
            !empty($messages) ? __($messagesString) : __('Transaction has been declined. Please try again later.')
        );
    }
}
