<?php

// phpcs:ignoreFile

/*
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

use Magento\Framework\App\Bootstrap;

try {
    require __DIR__ . '/../../../../../../app/bootstrap.php';
} catch (\Exception $e) {
    echo 'Bootstrap load failed';
    exit(1);
}
$bootstrap = Bootstrap::create(BP, $_SERVER);
$om = $bootstrap->getObjectManager();
$storeManager = $om->get(\Magento\Store\Model\StoreManagerInterface::class);
$url = $storeManager->getStore()->getBaseUrl();
$qaPrefixMatch = null;
preg_match('/qa(?<qa_id>[\d]+)/', $url, $qaPrefixMatch);
$prefix = $qaPrefixMatch['qa_id'] ?? '';
$prefix .= '+';
$customerEmails = [
    'Accepted' => 'test+%sdp_fraud_accepted-paymentplan_offered@hokodo.co',
    'Rejected' => 'test+%sdp_fraud_rejected-paymentplan_offered@hokodo.co',
    'Declined' => 'test+%sdp_fraud_accepted-paymentplan_declined@hokodo.co',
    'Partly offered' => 'test+%sdp_fraud_accepted-paymentplan_partly_offered@hokodo.co',
];

$customerFactory = $om->get(\Magento\Customer\Model\CustomerFactory::class);
foreach ($customerEmails as $type => $customerEmail) {
    try {
        $email = sprintf($customerEmail, $prefix);
        $customer = $customerFactory->create();
        $customer->setWebsiteId(1)->loadByEmail($email);
        if (!$customer->getId()) {
            $customer->setWebsiteId(1)
                ->setId('')
                ->setEmail($email)
                ->setFirstname('Test')
                ->setLastname($type)
                ->setPassword($argv[1])
                ->setGroupId(1)
                ->setStoreId(1)
                ->setIsActive(1)
                ->setDefaultBilling(1)
                ->setDefaultShipping(1);
            $customer->isObjectNew(true);
            $customer->save();
            echo 'Customer Test ' . $type . ' was created.' . PHP_EOL;
        } else {
            $customer->setPassword($argv[1])->save();
            echo 'Customer Test ' . $type . ' is already exists.' . PHP_EOL;
        }
    } catch (\Exception $e) {
        echo 'There was an error during customer Test ' . $type . ' creation.' . $e->getMessage() . PHP_EOL;
    }
}
