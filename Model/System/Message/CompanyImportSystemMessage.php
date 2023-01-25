<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Hokodo\BNPL\Model\System\Message;

use Magento\Framework\Notification\MessageInterface;

class CompanyImportSystemMessage implements MessageInterface
{
    public const MESSAGE_IDENTITY = 'hokodo_bnpl_import_system_message';

    /**
     * Retrieve unique system message identity.
     *
     * @return string
     */
    public function getIdentity()
    {
        return self::MESSAGE_IDENTITY;
    }

    /**
     * Check whether the system message should be shown.
     *
     * @return bool
     */
    public function isDisplayed()
    {
        // The message will be shown
        return true;
    }

    /**
     * Retrieve system message text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getText()
    {
        return __('Custom message.');
    }

    /**
     * Retrieve system message severity.
     *
     * @return int
     */
    public function getSeverity()
    {
        return self::SEVERITY_MAJOR;
    }
}
