<?php
/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway;

use Hokodo\BNPL\Api\Data\PaymentOffersInterface;

/**
 * Class Hokodo\BNPL\Gateway\PaymentOfferSubjectReader.
 */
class PaymentOfferSubjectReader extends SubjectReader
{
    /**
     * @var OrderSubjectReader
     */
    private $orderSubjectReader;

    /**
     * @param OrderSubjectReader $orderSubjectReader
     */
    public function __construct(OrderSubjectReader $orderSubjectReader)
    {
        $this->orderSubjectReader = $orderSubjectReader;
    }

    /**
     * A function that read order id.
     *
     * @param array $subject
     *
     * @return string
     */
    public function readOrderId(array $subject)
    {
        return $this->orderSubjectReader->readOrder($subject)->getId();
    }

    /**
     * A function that read offer.
     *
     * @param array $subject
     *
     * @throws \InvalidArgumentException
     *
     * @return \Hokodo\BNPL\Api\Data\PaymentOffersInterface
     */
    public function readOffer(array $subject)
    {
        $offer = $this->readFieldValue('offers', $subject);

        if (!($offer instanceof PaymentOffersInterface)) {
            throw new \InvalidArgumentException(__('Offer field should be provided'));
        }

        return $offer;
    }

    /**
     * @inheritDoc
     *
     * @see \Hokodo\BNPL\Gateway\SubjectReader::readEndpointParam()
     */
    public function readEndpointParam($param, array $subject)
    {
        if ($param != 'offer_id') {
            throw new \InvalidArgumentException(__('For endopoint offer param should be offer_id'));
        }
        return $this->readOffer($subject)->getId();
    }
}
