import { expect } from "@playwright/test";
import test from "../fixtures";
import { verifyAddressDetails, verifyHokodoOrder } from "../support/playwright-assertion-helpers";
import { BuyerStatus, CompanyType, CreditStatus, FraudStatus } from "../support/types/Buyer";

test.describe("Full end to end for Registered Buyers", () => {
  test("Placing and fulfilling a Registered Company's first Order", async ({
    homePage,
    productDetailsPage,
    shippingAddressPage,
    paymentPage,
    adminLoginPage,
    adminHomePage,
    listOrdersPage,
    orderPage,
    shipOrderPage,
    hokodoApi,
    generateOrderData,
    createAccountPage,
    magentoApi,
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
    await shippingAddressPage.setupNewShippingAddress(testOrderData.shippingAddress, "flatrate_flatrate");

    // load payment page
    await paymentPage.navigate();
    const basketDetails = await paymentPage.getBasketDetails();

    // find registered company
    await paymentPage.selectHokodo();
    await paymentPage.hokodoCheckout.findRegisteredCompany(testOrderData.buyer);

    // pay with Hokodo
    await paymentPage.hokodoCheckout.selectAPaymentPlan();
    await paymentPage.hokodoCheckout.selectPaymentMethod("invoice");
    await paymentPage.hokodoCheckout.acceptTermsAndConditions();
    const orderId = await paymentPage.hokodoCheckout.createDeferredPayment();

      // fetch order from Hokodo
    let order = await hokodoApi.viewOrder(orderId);

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
    await adminLoginPage.loginToAdmin();
    await adminHomePage.navigateToListOrdersPage();

    // Order ID are updated async after DP is created in Magento. Delaying this call as late as possible to factor this in.
    order = await hokodoApi.viewOrder(orderId);

    await listOrdersPage.navigateToOrderPage(order.unique_id);
    await orderPage.captureInvoice(order.unique_id, magentoApi);

    // fetch the updated Hokodo Order
    order = await hokodoApi.viewOrder(orderId);

    const deferredPayment = await hokodoApi.waitForDeferredPaymentToReachStatus(order.deferred_payment.id, "captured")
    
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

  test("Placing and fulfilling a Sole Trader's first Order", async ({
    homePage,
    productDetailsPage,
    shippingAddressPage,
    paymentPage,
    adminLoginPage,
    adminHomePage,
    listOrdersPage,
    orderPage,
    shipOrderPage,
    hokodoApi,
    generateOrderData,
    createAccountPage,
    magentoApi
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
    await shippingAddressPage.setupNewShippingAddress(testOrderData.shippingAddress, "flatrate_flatrate");
    
    // load payment page
    await paymentPage.navigate();
    const basketDetails = await paymentPage.getBasketDetails();

    // setup sole trader
    await paymentPage.selectHokodo();
    await paymentPage.hokodoCheckout.setupSoleTrader(testOrderData.buyer);

    // pay with Hokodo
    await paymentPage.hokodoCheckout.selectAPaymentPlan();
    await paymentPage.hokodoCheckout.selectPaymentMethod("invoice");
    await paymentPage.hokodoCheckout.acceptTermsAndConditions();
    const orderId = await paymentPage.hokodoCheckout.createDeferredPayment();
    
    // fetch order from Hokodo
    let order = await hokodoApi.viewOrder(orderId);

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
    await adminLoginPage.loginToAdmin();
    await adminHomePage.navigateToListOrdersPage();

    // Order ID are updated async after DP is created in Magento. Delaying this call as late as possible to factor this in.
    order = await hokodoApi.viewOrder(orderId);

    await listOrdersPage.navigateToOrderPage(order.unique_id);
    await orderPage.captureInvoice(order.unique_id, magentoApi);

    // fetch the updated Hokodo Order
    order = await hokodoApi.viewOrder(orderId);

    // fetch the Hokodo Deferred Payment
    const deferredPayment = await hokodoApi.waitForDeferredPaymentToReachStatus(order.deferred_payment.id, "captured")
    
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