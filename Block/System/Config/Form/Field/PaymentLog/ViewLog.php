<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Hokodo\BNPL\Block\System\Config\Form\Field\PaymentLog;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class ViewLog extends Field
{
    /**
     * The method responsible for rendering.
     *
     * @param AbstractElement $element
     *
     * @return string
     */
    public function render(AbstractElement $element): string
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * The method responsible for preparing layout.
     *
     * @return $this
     */
    protected function _prepareLayout(): ViewLog
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('system/config/viewlog.phtml');
        }
        return $this;
    }

    /**
     * The method used for getting the element.
     *
     * @param AbstractElement $element
     *
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        $originalData = $element->getOriginalData();
        $buttonLabel = !empty($originalData['button_label']) ? $originalData['button_label'] : __('View Log');

        $this->addData(
            [
                'button_label' => __($buttonLabel),
                'html_id' => $element->getHtmlId(),
                'page_url' => $this->_urlBuilder->getUrl('hokodo/paymentlog'),
            ]
        );
        return $this->_toHtml();
    }
}
