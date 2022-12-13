import { expect } from "@playwright/test";
import test from "../fixtures";
import { getCaptureStatus, getHokodoIdsFromMagentoOrder } from "../support/playwright-test-helpers";
import { CompanyType, CreditStatus, FraudStatus } from "../support/types/Buyer";
import { MagentoOrderCaptureStatus } from "../support/types/MagentoOrder";

test.describe("Post Sale Admin Actions", () => {
  test("Can't fulfil a pending review Deferred Payment", async ({
    homePage,
    generateOrderData,
    productDetailsPage,
    shippingAddressPage,
    paymentPage,
    adminLoginPage,
    orderPage,
    hokodoApi,
    magentoApi,
  }) => {
    const testOrderData = await generateOrderData(CompanyType.REGISTERED_COMPANY, { creditStatus: CreditStatus.OFFERED, fraudStatus: FraudStatus.PENDING_REVIEW});
    
    // add products to the basket
    for (const product of testOrderData.products) {
      await homePage.navigate();
      await homePage.addItemToBasket(product.name);
      await productDetailsPage.selectVariant(product.size);
      await productDetailsPage.selectVariant(product.colour);
      await productDetailsPage.setQuantity(product.quantity);
      await productDetailsPage.addToBasket();
    }

    // enter shipping details
    await shippingAddressPage.setupNewShippingAddress(testOrderData, "flatrate_flatrate");

    // load payment page
    await paymentPage.navigate();

    // find registered company
    await paymentPage.selectHokodo();
    await paymentPage.hokodoCheckout.findRegisteredCompany(testOrderData.buyer);

    // pay with Hokodo
    await paymentPage.hokodoCheckout.selectAPaymentPlan();
    await paymentPage.hokodoCheckout.selectPaymentMethod("invoice");
    await paymentPage.hokodoCheckout.acceptTermsAndConditions();
    const magentoOrderId = await paymentPage.hokodoCheckout.createDeferredPayment();
    
    await adminLoginPage.navigate();
    await adminLoginPage.loginToAdmin();

    const magentoOrder = await magentoApi.getOrder(magentoOrderId);
    const hokodoIds = getHokodoIdsFromMagentoOrder(magentoOrder);
    
    await hokodoApi.waitForDeferredPaymentToReachStatus(hokodoIds.deferredPayment, "pending_review");

    await orderPage.navigate(magentoOrder.entity_id);

    expect(await orderPage.canShipOrder(), "The Order can be shipped").toBe(false);
  });

  test.only("Cancel an Order in the Admin Portal", async ({
    homePage,
    generateOrderData,
    productDetailsPage,
    shippingAddressPage,
    paymentPage,
    adminLoginPage,
    orderPage,
    hokodoApi,
    magentoApi,
  }) => {
    const testOrderData = await generateOrderData(CompanyType.REGISTERED_COMPANY, { creditStatus: CreditStatus.OFFERED, fraudStatus: FraudStatus.ACCEPTED});
    
    // add products to the basket
    for (const product of testOrderData.products) {
      await homePage.navigate();
      await homePage.addItemToBasket(product.name);
      await productDetailsPage.selectVariant(product.size);
      await productDetailsPage.selectVariant(product.colour);
      await productDetailsPage.setQuantity(product.quantity);
      await productDetailsPage.addToBasket();
    }

    // enter shipping details
    await shippingAddressPage.setupNewShippingAddress(testOrderData, "flatrate_flatrate");

    // load payment page
    await paymentPage.navigate();

    // find registered company
    await paymentPage.selectHokodo();
    await paymentPage.hokodoCheckout.findRegisteredCompany(testOrderData.buyer);

    // pay with Hokodo
    await paymentPage.hokodoCheckout.selectAPaymentPlan();
    await paymentPage.hokodoCheckout.selectPaymentMethod("invoice");
    await paymentPage.hokodoCheckout.acceptTermsAndConditions();
    const magentoOrderId = await paymentPage.hokodoCheckout.createDeferredPayment();
    
    await adminLoginPage.navigate();
    await adminLoginPage.loginToAdmin();
    
    const magentoOrder = await magentoApi.getOrder(magentoOrderId);

    test.skip(getCaptureStatus(magentoOrder) !== MagentoOrderCaptureStatus.NotInvoiced, "The Store has been configured to auto-capture Invoices. The Order cannot be cancelled");
    
    const hokodoIds = getHokodoIdsFromMagentoOrder(magentoOrder);

    await hokodoApi.waitForDeferredPaymentToReachStatus(hokodoIds.deferredPayment, "accepted");

    await orderPage.navigate(magentoOrder.entity_id);

    await orderPage.cancelOrder();

    const deferredPayment = await hokodoApi.waitForDeferredPaymentToReachStatus(hokodoIds.deferredPayment, "voided");

    expect(deferredPayment.voided_authorisation, "Deferred Payment voided_authorisation").toBe(magentoOrder.grand_total * 100);
  });  
});