<?php

declare(strict_types=1);

namespace Hokodo\BNPL\ViewModel\Checkout;

use Magento\Checkout\Model\Session;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Component\ComponentRegistrarInterface;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class OnepageSuccess implements ArgumentInterface
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var ComponentRegistrarInterface
     */
    private $componentRegistrar;

    /**
     * @var ReadFactory
     */
    private $readFactory;

    /**
     * OnepageSuccess constructor.
     *
     * @param Session                     $session
     * @param ComponentRegistrarInterface $componentRegistrar
     * @param ReadFactory                 $readFactory
     */
    public function __construct(
        Session $session,
        ComponentRegistrarInterface $componentRegistrar,
        ReadFactory $readFactory
    ) {
        $this->session = $session;
        $this->componentRegistrar = $componentRegistrar;
        $this->readFactory = $readFactory;
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

    /**
     * Get module composer version.
     *
     * @param string $moduleName
     *
     * @return Phrase|string|void
     */
    public function getModuleVersion($moduleName)
    {
        $path = $this->componentRegistrar->getPath(
            ComponentRegistrar::MODULE,
            $moduleName
        );
        $directoryRead = $this->readFactory->create($path);
        $composerJsonData = $directoryRead->readFile('composer.json');
        $data = json_decode($composerJsonData);

        return !empty($data->version) ? $data->version : __('Read error!');
    }
}
