<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Controller\Ipn;

use Exception;
use Hokodo\BNPL\Model\SaveLog as Logger;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\RemoteServiceUnavailableException;

/**
 * Class Hokodo\BNPL\Controller\Ipn\Index.
 */
class Index extends Action implements CsrfAwareActionInterface
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * A constructor.
     *
     * @param Context $context
     * @param Logger  $logger
     */
    public function __construct(
        Context $context,
        Logger $logger
    ) {
        parent::__construct($context);
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     *
     * @throws LocalizedException
     *
     * @see \Magento\Framework\App\ActionInterface::execute()
     */
    public function execute()
    {
        if (!$this->getRequest()->isPost()) {
            return;
        }

        try {
            $data = $this->getRequest()->getContent();
            //$this->_ipnFactory->create(['data' => $data])->processIpnRequest();
            $log = [
                'payment_log_content' => $data,
                'action_title' => 'Ipn::execute',
                'status' => 1,
            ];
        } catch (RemoteServiceUnavailableException $e) {
            $log = [
                'payment_log_content' => $e->getMessage(),
                'action_title' => 'Ipn::RemoteServiceUnavailableException:',
                'status' => 0,
            ];
            $this->getResponse()->setStatusHeader(503, '1.1', 'Service Unavailable');
        } catch (Exception $e) {
            $log = [
                'payment_log_content' => $e->getMessage(),
                'action_title' => 'Ipn::Exception:',
                'status' => 0,
            ];
            $this->getResponse()->setHttpResponseCode(500);
        }
        $this->logger->execute($log);
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\App\CsrfAwareActionInterface::createCsrfValidationException()
     */
    public function createCsrfValidationException(
        RequestInterface $request
    ): ?InvalidRequestException {
        return null;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\App\CsrfAwareActionInterface::validateForCsrf()
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }
}
