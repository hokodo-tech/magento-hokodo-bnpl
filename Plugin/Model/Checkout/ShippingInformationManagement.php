<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Plugin\Model\Checkout;

use Hokodo\BNPL\Model\SaveLog as PaymentLogger;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\ResourceConnection;
use Magento\Quote\Model\Quote;

class ShippingInformationManagement
{
    /**
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * Variable.
     *
     * @var Quote
     */
    protected $_quote = null;

    /**
     * @var PaymentLogger
     */
    protected $paymentLogger;

    /**
     * A construct.
     *
     * @param ResourceConnection $resource
     * @param Session            $checkoutSession
     * @param PaymentLogger      $paymentLogger
     */
    public function __construct(
        ResourceConnection $resource,
        Session $checkoutSession,
        PaymentLogger $paymentLogger
    ) {
        $this->resource = $resource;
        $this->checkoutSession = $checkoutSession;
        $this->paymentLogger = $paymentLogger;
    }

    /**
     * A function that saves address information.
     *
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param string                                                $cartId
     * @param ShippingInformationInterface                          $addressInformation
     *
     * @return void
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        $log = [];
        $log[] = 'Before save address information';
        $removeEmailAddressFromQuote = 'UPDATE ' . $this->resource->getTableName('quote_address') .
            " SET email='' where quote_id=" . $this->getQuote()->getId();
        $this->resource->getConnection()->query($removeEmailAddressFromQuote);
        $log[] = 'Remove address from quote: ' . $removeEmailAddressFromQuote;
        $data = [
            'payment_log_content' => $log,
            'action_title' => 'ShippingInformationManagement: beforeSaveAddressInformation',
            'status' => 1,
            'quote_id' => $this->getQuote()->getId(),
        ];
        $this->paymentLogger->execute($data);
    }

    /**
     * A function that gets a quote.
     *
     * @return Quote|null
     */
    public function getQuote()
    {
        if ($this->_quote === null) {
            return $this->checkoutSession->getQuote();
        }
        return $this->_quote;
    }
}
