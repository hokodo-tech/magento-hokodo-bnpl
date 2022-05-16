<?php
/**
 * Copyright © 2018-2021 Hokodo. All Rights Reserved.
 * See LICENSE for license details.
 */

namespace Hokodo\BNPL\Controller\Adminhtml\Organization;

use Hokodo\BNPL\Model\HokodoOrganisationRepository;
use Hokodo\BNPL\Service\OrganisationService;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Customer\Model\Address\Mapper;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\DataObjectFactory as ObjectFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\SecurityViolationException;

/**
 * Class Hokodo\BNPL\Controller\Adminhtml\Organization\Reset.
 */
class Reset extends \Magento\Customer\Controller\Adminhtml\Index implements HttpGetActionInterface
{
    /**
     * @var HokodoOrganisationRepository
     */
    private $hokodoOrganisationRepository;

    /**
     * @var OrganisationService
     */
    private $organisationService;

    /**
     * @param \Magento\Backend\App\Action\Context                  $context
     * @param \Magento\Framework\Registry                          $coreRegistry
     * @param \Magento\Framework\App\Response\Http\FileFactory     $fileFactory
     * @param \Magento\Customer\Model\CustomerFactory              $customerFactory
     * @param \Magento\Customer\Model\AddressFactory               $addressFactory
     * @param \Magento\Customer\Model\Metadata\FormFactory         $formFactory
     * @param \Magento\Newsletter\Model\SubscriberFactory          $subscriberFactory
     * @param \Magento\Customer\Helper\View                        $viewHelper
     * @param \Magento\Framework\Math\Random                       $random
     * @param CustomerRepositoryInterface                          $customerRepository
     * @param \Magento\Framework\Api\ExtensibleDataObjectConverter $extensibleDataObjectConverter
     * @param Mapper                                               $addressMapper
     * @param AccountManagementInterface                           $customerAccountManagement
     * @param AddressRepositoryInterface                           $addressRepository
     * @param CustomerInterfaceFactory                             $customerDataFactory
     * @param AddressInterfaceFactory                              $addressDataFactory
     * @param \Magento\Customer\Model\Customer\Mapper              $customerMapper
     * @param \Magento\Framework\Reflection\DataObjectProcessor    $dataObjectProcessor
     * @param DataObjectHelper                                     $dataObjectHelper
     * @param ObjectFactory                                        $objectFactory
     * @param \Magento\Framework\View\LayoutFactory                $layoutFactory
     * @param \Magento\Framework\View\Result\LayoutFactory         $resultLayoutFactory
     * @param \Magento\Framework\View\Result\PageFactory           $resultPageFactory
     * @param \Magento\Backend\Model\View\Result\ForwardFactory    $resultForwardFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory     $resultJsonFactory
     * @param HokodoOrganisationRepository                         $hokodoOrganisationRepository
     * @param OrganisationService                                  $organisationService
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\AddressFactory $addressFactory,
        \Magento\Customer\Model\Metadata\FormFactory $formFactory,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Magento\Customer\Helper\View $viewHelper,
        \Magento\Framework\Math\Random $random,
        CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Api\ExtensibleDataObjectConverter $extensibleDataObjectConverter,
        Mapper $addressMapper,
        AccountManagementInterface $customerAccountManagement,
        AddressRepositoryInterface $addressRepository,
        CustomerInterfaceFactory $customerDataFactory,
        AddressInterfaceFactory $addressDataFactory,
        \Magento\Customer\Model\Customer\Mapper $customerMapper,
        \Magento\Framework\Reflection\DataObjectProcessor $dataObjectProcessor,
        DataObjectHelper $dataObjectHelper,
        ObjectFactory $objectFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        HokodoOrganisationRepository $hokodoOrganisationRepository,
        OrganisationService $organisationService
    ) {
        parent::__construct(
            $context,
            $coreRegistry,
            $fileFactory,
            $customerFactory,
            $addressFactory,
            $formFactory,
            $subscriberFactory,
            $viewHelper,
            $random,
            $customerRepository,
            $extensibleDataObjectConverter,
            $addressMapper,
            $customerAccountManagement,
            $addressRepository,
            $customerDataFactory,
            $addressDataFactory,
            $customerMapper,
            $dataObjectProcessor,
            $dataObjectHelper,
            $objectFactory,
            $layoutFactory,
            $resultLayoutFactory,
            $resultPageFactory,
            $resultForwardFactory,
            $resultJsonFactory
        );

        $this->hokodoOrganisationRepository = $hokodoOrganisationRepository;
        $this->organisationService = $organisationService;
    }

    /**
     * @inheritDoc
     *
     * @see \Magento\Framework\App\ActionInterface::execute()
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $customerId = (int) $this->getRequest()->getParam('customer_id', 0);
        if (!$customerId) {
            $resultRedirect->setPath('customer/index');
            return $resultRedirect;
        }

        try {
            $customer = $this->_customerRepository->getById($customerId);

            /**
             * @var \Magento\Framework\Api\AttributeInterface $hokodoUserId
             */
            $hokodoUserId = $customer->getCustomAttribute('hokodo_user_id');
            /**
             * @var \Magento\Framework\Api\AttributeInterface $hokodoUserId
             */
            $hokodoOrganizationId = $customer->getCustomAttribute('hokodo_organization_id');
            if ($hokodoUserId && $hokodoUserId->getValue()
                && $hokodoOrganizationId && $hokodoOrganizationId->getValue()) {
                try {
                    /**
                     * @var \Hokodo\BNPL\Model\HokodoOrganisation $hokodoOrganisation
                     */
                    $hokodoOrganisation = $this->hokodoOrganisationRepository->getById(
                        $hokodoOrganizationId->getValue()
                    );
                } catch (NoSuchEntityException $e) {
                    $customer->setCustomAttribute('hokodo_organization_id', 0);
                    $hokodoOrganisation = null;
                }

                if ($hokodoOrganisation && $hokodoOrganisation->getId()) {
                    /**
                     * @var \Hokodo\BNPL\Api\Data\OrganisationInterface $organisation
                     */
                    $organisation = $this->organisationService->get($hokodoOrganisation->getDataModel());

                    /**
                     * @var \Hokodo\BNPL\Api\Data\OrganisationUserInterface $user
                     */
                    foreach ($organisation->getUsers() as $user) {
                        if ($user->getId() == $hokodoUserId->getValue()) {
                            $this->organisationService->removeUser($organisation, $user);
                            break;
                        }
                    }
                }

                $customer->setCustomAttribute('hokodo_organization_id', 0);
            } else {
                $customer->setCustomAttribute('hokodo_organization_id', 0);
            }

            $this->_customerRepository->save($customer);

            $this->messageManager->addSuccessMessage(
                __('Reset organisation successfully complete.')
            );
        } catch (NoSuchEntityException $exception) {
            $resultRedirect->setPath('customer/index');
            return $resultRedirect;
        } catch (\Magento\Framework\Validator\Exception $exception) {
            $messages = $exception->getMessages(\Magento\Framework\Message\MessageInterface::TYPE_ERROR);
            if (!count($messages)) {
                $messages = $exception->getMessage();
            }
            $this->_addSessionErrorMessages($messages);
        } catch (SecurityViolationException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        } catch (\Exception $exception) {
            $this->messageManager->addExceptionMessage(
                $exception,
                __('Something went wrong while resetting customer organisation.')
            );
        }
        $resultRedirect->setPath(
            'customer/index/edit',
            ['id' => $customerId, '_current' => true]
        );
        return $resultRedirect;
    }
}
