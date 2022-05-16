<?php

declare(strict_types=1);

namespace Hokodo\BNPL\Block\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class PaymentLog extends Field
{
    /**
     * The render method.
     *
     * @param AbstractElement $element
     *
     * @return string
     */
    public function render(AbstractElement $element): string
    {
        $element
            ->setCanUseDefaultValue(false)
            ->setCanUseWebsiteValue(false)
            ->setCanRestoreToDefault(false)
            ->setInherit(false);

        return parent::render($element);
    }
}
