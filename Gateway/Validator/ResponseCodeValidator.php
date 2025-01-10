<?php

/**
 * Copyright © 2018-2023 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Gateway\Validator;

use Magento\Framework\Stdlib\ArrayUtils;
use Magento\Payment\Gateway\Validator\AbstractValidator;
use Magento\Payment\Gateway\Validator\ResultInterfaceFactory;

/**
 * Class Hokodo\BNPL\Gateway\Validator\ResponseCodeValidator.
 */
class ResponseCodeValidator extends AbstractValidator
{
    /**
     * @var ArrayUtils
     */
    private $arrayUtils;

    /**
     * @param ResultInterfaceFactory $resultFactory
     * @param ArrayUtils             $arrayUtils
     */
    public function __construct(
        ResultInterfaceFactory $resultFactory,
        ArrayUtils $arrayUtils
    ) {
        parent::__construct($resultFactory);
        $this->arrayUtils = $arrayUtils;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Payment\Gateway\Validator\ValidatorInterface::validate()
     */
    public function validate(array $validationSubject)
    {
        if (!isset($validationSubject['response'])) {
            throw new \InvalidArgumentException(__('Response does not exist'));
        }

        $response = $validationSubject['response'];

        if ($response instanceof \Psr\Http\Message\ResponseInterface) {
            $level = (int) floor($response->getStatusCode() / 100);
            $body = (string) $response->getBody();

            if ($level === 4 && $body) {
                $body = json_decode($body, true);
                $errorCodes = array_keys($this->arrayUtils->flatten($body, '', '_'));
                return $this->createResult(
                    false,
                    $body ?? [],
                    $errorCodes
                );
            } else {
                return $this->createResult(
                    false,
                    [__('Hokodo BNPL gateway has rejected request.')]
                );
            }
        }

        return $this->createResult(
            true,
            []
        );
    }
}
