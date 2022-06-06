<?php

declare(strict_types=1);

namespace Hokodo\BNPL\Gateway\Request\Authorization;

use Hokodo\BNPL\Gateway\Config\Config;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Sales\Api\OrderRepositoryInterface;

class DocumentAuthorizationBuilder extends \Hokodo\BNPL\Gateway\Request\AuthorizationBuilder
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var SearchCriteriaBuilderFactory
     */
    private $criteriaBuilderFactory;

    /**
     * DocumentAuthorizationBuilder constructor.
     *
     * @param Config                       $config
     * @param OrderRepositoryInterface     $orderRepository
     * @param SearchCriteriaBuilderFactory $criteriaBuilderFactory
     */
    public function __construct(
        Config $config,
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilderFactory $criteriaBuilderFactory
    ) {
        parent::__construct(
            $config
        );
        $this->orderRepository = $orderRepository;
        $this->criteriaBuilderFactory = $criteriaBuilderFactory;
    }

    /**
     * Builds Authorization header.
     *
     * @param array $buildSubject
     *
     * @return array[]
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function build(array $buildSubject): array
    {
        $storeId = null;
        if (isset($buildSubject['order_id']) && $buildSubject['order_id']) {
            $searchCriteriaBuilder = $this->criteriaBuilderFactory->create();
            $searchCriteriaBuilder->addFilter('order_api_id', $buildSubject['order_id']);
            $result = $this->orderRepository->getList($searchCriteriaBuilder->create());
            if ($result->getTotalCount() > 0) {
                foreach ($result->getItems() as $order) {
                    $storeId = (int) $order->getStoreId();
                }
            }
        }
        return [
            'header' => [
                'Authorization' => sprintf('Token %s', $this->getAuthorizationKey($storeId)),
            ],
        ];
    }
}
