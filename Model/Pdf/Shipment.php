<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Pdf;

use Magento\Sales\Model\Order\Pdf\Shipment as PdfShipment;

/**
 * Class Hokodo\BNPL\Model\Shipment.
 * Reduce PDF font size.
 */
class Shipment extends PdfShipment
{
    /**
     * @inheritDoc
     *
     * @see \Magento\Sales\Model\Order\Pdf\Shipment::_setFontRegular()
     */
    protected function _setFontRegular($object, $size = 7)
    {
        $font = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_TIMES);
        $object->setFont($font, $size);
        return $font;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Sales\Model\Order\Pdf\Shipment::_setFontBold()
     */
    protected function _setFontBold($object, $size = 7)
    {
        $font = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_TIMES_BOLD);
        $object->setFont($font, $size);
        return $font;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Sales\Model\Order\Pdf\Shipment::_setFontItalic()
     */
    protected function _setFontItalic($object, $size = 7)
    {
        $font = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_TIMES_ITALIC);
        $object->setFont($font, $size);
        return $font;
    }
}
