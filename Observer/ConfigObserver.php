<?php

namespace Hokodo\BNPL\Observer;

use Hokodo\BNPL\Model\SaveLog as Logger;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class ConfigObserver implements ObserverInterface
{
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var mixed
     */
    protected $resource;

    /**
     * A construct.
     *
     * @param Logger                                    $logger
     * @param \Magento\Framework\App\ResourceConnection $resource
     */
    public function __construct(
        Logger $logger,
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->logger = $logger;
        $this->resource = $resource;
    }

    /**
     * A function that execute event observer.
     *
     * @param EventObserver $observer
     *
     * @return void
     *
     * @throws \Exception
     */
    public function execute(EventObserver $observer)
    {
        $log = [];
        $s = 'DELETE from ' . $this->resource->getTableName('hokodo_payment_quote'); // @codingStandardsIgnoreLine
        $log['delete_hokodo_payment_quote'] = $s;

        $this->resource->getConnection()->query($s);

        $s = 'DELETE from ' . $this->resource->getTableName('hokodo_organisation'); // @codingStandardsIgnoreLine
        $log['delete_hokodo_organisation'] = $s;
        $this->resource->getConnection()->query($s);
        $data = [
            'payment_log_content' => $log,
            'action_title' => 'ConfigObserver: execute',
            'status' => 1,
        ];
        $this->logger->execute($data);
    }
}
