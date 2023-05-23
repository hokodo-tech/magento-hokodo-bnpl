<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Model\Pdf;

use Magento\Sales\Model\Order\Pdf\Creditmemo as PdfCreditmemo;

/**
 * Class Hokodo\BNPL\Model\Invoice.
 * Reduce PDF font size.
 */
class Creditmemo extends PdfCreditmemo
{
    /**
     * @inheritDoc
     *
     * @see \Magento\Sales\Model\Order\Pdf\Invoice::_setFontRegular()
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
     * @see \Magento\Sales\Model\Order\Pdf\Invoice::_setFontBold()
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
     * @see \Magento\Sales\Model\Order\Pdf\Invoice::_setFontItalic()
     */
    protected function _setFontItalic($object, $size = 7)
    {
        $font = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_TIMES_ITALIC);
        $object->setFont($font, $size);
        return $font;
    }
}
