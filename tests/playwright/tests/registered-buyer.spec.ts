import { expect } from "@playwright/test";
import test from "../fixtures";
import { verifyAddressDetails, verifyHokodoOrder } from "../support/playwright-assertion-helpers";
import { getCaptureStatus, getHokodoIdsFromMagentoOrder } from "../support/playwright-test-helpers";
import { CompanyType, DeferredPaymentStatus } from "../support/types/Buyer";
import { MagentoOrderCaptureStatus } from "../support/types/MagentoOrder";

test.describe("Full end to end for Registered Buyers", () => {
  test("Placing and fulfilling a Registered Company's first Order", async ({
    homePage,
    productDetailsPage,
    shippingAddressPage,
    paymentPage,
    adminLoginPage,
    orderPage,
    shipOrderPage,
    hokodoApi,
    generateOrderData,
    createAccountPage,
    magentoApi,
    buyerLoginPage,
  }) => {
    const testOrderData = await generateOrderData(CompanyType.REGISTERED_COMPANY);

    await createAccountPage.navigate();
    await createAccountPage.createAccount(testOrderData.buyer);

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
    const basketDetails = await paymentPage.getBasketDetails();

    // find registered company
    await paymentPage.selectHokodo();
    await paymentPage.hokodoCheckout.findRegisteredCompany(testOrderData.buyer);

    // pay with Hokodo
    await paymentPage.hokodoCheckout.selectAPaymentPlan();
    await paymentPage.hokodoCheckout.selectPaymentMethod("invoice");
    const magentoOrderId = await paymentPage.hokodoCheckout.placeOrder();

    const magentoOrder = await magentoApi.getOrder(magentoOrderId);
    const hokodoIds = getHokodoIdsFromMagentoOrder(magentoOrder);
    
      // fetch order from Hokodo
    let hokodoOrder = await hokodoApi.getOrder(hokodoIds.order);

    // ensure the order items at Hokodo match the items that were in the basket
    verifyHokodoOrder(hokodoOrder, basketDetails.totals);

    verifyAddressDetails(testOrderData.billingAddress, hokodoOrder.customer.invoice_address);
    verifyAddressDetails(testOrderData.shippingAddress, hokodoOrder.customer.delivery_address);

    // ensure that one Organisation and One user was created for this Order
    const organisation = await hokodoApi.viewOrganisation(hokodoOrder.customer.organisation);

    expect(organisation.users, "Make sure that only one user is linked to the Organisation").toHaveLength(1);
    expect(organisation.users[0].email, "Ensure the correct user is added to the Organisation").toBe(testOrderData.buyer.email);
    expect(organisation.users[0].role, "Ensure the user has the correct role").toBe("member");
        
    await adminLoginPage.navigate();
    await adminLoginPage.login();

    await orderPage.navigate(magentoOrder.entity_id);

    // capture the Magento order if it hasn't already been captured
    if (getCaptureStatus(await magentoApi.getOrder(magentoOrderId)) === MagentoOrderCaptureStatus.NotInvoiced) {
      await hokodoApi.waitForDeferredPaymentToReachStatus(hokodoIds.deferredPayment, DeferredPaymentStatus.ACCEPTED);  
      await orderPage.captureInvoice();
    }

    const deferredPayment = await hokodoApi.waitForDeferredPaymentToReachStatus(hokodoIds.deferredPayment, DeferredPaymentStatus.CAPTURED)
    
    expect(deferredPayment.authorisation, "Deferred Payment authorisation").toBe(0);
    expect(deferredPayment.protected_captures, "Deferred Payment protected_captures").toBe(basketDetails.totals.grand_total * 100);
    expect(deferredPayment.unprotected_captures, "Deferred Payment unprotected_captures").toBe(0);
    expect(deferredPayment.refunds, "Deferred Payment refunds").toBe(0);
    expect(deferredPayment.voided_authorisation, "Deferred Payment voided_authorisation").toBe(0);
    expect(deferredPayment.expired_authorisation, "Deferred Payment expired_authorisation").toBe(0);

    await orderPage.navigateToShipOrderPage();
    await shipOrderPage.shipOrder();
  });

  test("Placing and fulfilling a Sole Trader's first Order", async ({
    homePage,
    productDetailsPage,
    shippingAddressPage,
    paymentPage,
    adminLoginPage,
    orderPage,
    shipOrderPage,
    hokodoApi,
    generateOrderData,
    createAccountPage,
    magentoApi,
    buyerLoginPage
  }) => {
    const testOrderData = await generateOrderData(CompanyType.SOLE_TRADER);

    await createAccountPage.navigate();
    await createAccountPage.createAccount(testOrderData.buyer);

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
    const basketDetails = await paymentPage.getBasketDetails();

    // setup sole trader
    await paymentPage.selectHokodo();
    await paymentPage.hokodoCheckout.setupSoleTrader(testOrderData.buyer);

    // pay with Hokodo
    await paymentPage.hokodoCheckout.selectAPaymentPlan();
    await paymentPage.hokodoCheckout.selectPaymentMethod("invoice");
    const magentoOrderId = await paymentPage.hokodoCheckout.placeOrder();

    const magentoOrder = await magentoApi.getOrder(magentoOrderId);
    const hokodoIds = getHokodoIdsFromMagentoOrder(magentoOrder);
    
    // fetch order from Hokodo
    let order = await hokodoApi.getOrder(hokodoIds.order);

    // ensure the order items at Hokodo match the items that were in the basket
    verifyHokodoOrder(order, basketDetails.totals);

    verifyAddressDetails(testOrderData.billingAddress, order.customer.invoice_address);
    verifyAddressDetails(testOrderData.shippingAddress, order.customer.delivery_address);

    // ensure that one Organisation and One user was created for this Order
    const organisation = await hokodoApi.viewOrganisation(order.customer.organisation);

    expect(organisation.users, "Make sure that only one user is linked to the Organisation").toHaveLength(1);
    expect(organisation.users[0].email, "Ensure the correct user is added to the Organisation").toBe(testOrderData.buyer.email);
    expect(organisation.users[0].role, "Ensure the user has the correct role").toBe("member");
    
    // ship the order in Magento
    await adminLoginPage.navigate();
    await adminLoginPage.login();

    await orderPage.navigate(magentoOrder.entity_id);

    // capture the Magento order if it hasn't already been captured
    if (getCaptureStatus(magentoOrder) === MagentoOrderCaptureStatus.NotInvoiced) {
      await hokodoApi.waitForDeferredPaymentToReachStatus(hokodoIds.deferredPayment, DeferredPaymentStatus.ACCEPTED);
      await orderPage.captureInvoice();
    }

    // fetch the Hokodo Deferred Payment
    const deferredPayment = await hokodoApi.waitForDeferredPaymentToReachStatus(hokodoIds.deferredPayment, DeferredPaymentStatus.CAPTURED)
    
    // expect(deferred_payment.status, "Deferred Payment Status").toBe("captured");
    expect(deferredPayment.authorisation, "Deferred Payment authorisation").toBe(0);
    expect(deferredPayment.protected_captures, "Deferred Payment protected_captures").toBe(basketDetails.totals.grand_total * 100);
    expect(deferredPayment.unprotected_captures, "Deferred Payment unprotected_captures").toBe(0);
    expect(deferredPayment.refunds, "Deferred Payment refunds").toBe(0);
    expect(deferredPayment.voided_authorisation, "Deferred Payment voided_authorisation").toBe(0);
    expect(deferredPayment.expired_authorisation, "Deferred Payment expired_authorisation").toBe(0);

    await orderPage.navigateToShipOrderPage();
    await shipOrderPage.shipOrder();
  });
});
