<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Block\Adminhtml\Customer\Edit;

use Magento\Customer\Block\Adminhtml\Edit\GenericButton;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class Hokodo\BNPL\Block\Adminhtml\Customer\Edit\ResetOrganizationButton.
 */
class ResetOrganizationButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface::getButtonData()
     */
    public function getButtonData()
    {
        $customerId = $this->getCustomerId();
        $data = [];
        if ($customerId) {
            $data = [
                'label' => __('Reset Hokodo Organisation'),
                'class' => 'reset reset-password',
                'on_click' => sprintf("location.href = '%s';", $this->getResetOrganizationUrl()),
                'sort_order' => 60,
            ];
        }
        return $data;
    }

    /**
     * A function that gets rest organization.
     *
     * @return string
     */
    public function getResetOrganizationUrl()
    {
        return $this->getUrl('customer/organization/reset', ['customer_id' => $this->getCustomerId()]);
    }
}
