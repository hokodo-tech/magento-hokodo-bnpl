<?php

declare(strict_types=1);

namespace Hokodo\BNPL\ViewModel\Checkout;

use Magento\Checkout\Model\Session;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class OnepageSuccess implements ArgumentInterface
{
    /**
     * @var Session
     */
    private $session;

    /**
     * OnepageSuccess constructor.
     *
     * @param Session $session
     */
    public function __construct(
        Session $session
    ) {
        $this->session = $session;
    }

    /**
     * Return payment method code.
     *
     * @return string
     */
    public function getSelectedPaymentMethodCode()
    {
        return $this->getOrder()->getPayment()->getMethod();
    }

    /**
     * Return last real order.
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->session->getLastRealOrder();
    }
}
