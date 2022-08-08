<?php
declare(strict_types=1);

namespace Hokodo\BNPL\Model\Data\Webapi;

use Hokodo\BNPL\Api\Data\Webapi\CreateOrganisationRequestInterface;
use Magento\Framework\DataObject;

class CreateOrganisationRequest extends DataObject implements CreateOrganisationRequestInterface
{

    /**
     * @return string
     */
    public function getCompanyId(): string
    {
        return $this->getData(self::COMPANY_ID);
    }

    /**
     * @param string $companyId
     *
     * @return CreateOrganisationRequestInterface
     */
    public function setCompanyId(string $companyId): CreateOrganisationRequestInterface
    {
        $this->setData(self::COMPANY_ID, $companyId);
        return $this;
    }

    /**
     * @param string $quoteId
     *
     * @return CreateOrganisationRequestInterface
     */
    public function setQuoteId(string $quoteId): CreateOrganisationRequestInterface
    {
        $this->setData(self::QUOTE_ID, $quoteId);
        return $this;
    }

    /**
     * @return string
     */
    public function getQuoteId(): string
    {
        return $this->getData(self::QUOTE_ID);
    }
}
