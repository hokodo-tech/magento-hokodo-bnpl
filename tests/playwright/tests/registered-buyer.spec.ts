import { expect } from "@playwright/test";
import test from "../fixtures";
import { HokodoAPI } from "../support/hokodo-api";

test.describe("Full end-to-end", () => {

  test.beforeEach(async ({ page }) => {

  });

  test("Placing and fulfilling an Order", async ({
    createAccountPage,
    homePage,
    orderData,
    productDetailsPage,
    shippingAddressPage,
    paymentPage,
    checkoutSuccessPage,
    adminLoginPage,
    adminHomePage,
    listOrdersPage,
    orderPage,
    shipOrderPage,
    page,
  }) => {
    await createAccountPage.navigate();
    await createAccountPage.createAccount(orderData.buyer);

    for (const product of orderData.products) {
      await homePage.navigate();
      await homePage.addItemToBasket(product.name);
      await productDetailsPage.selectVariant(product.size);
      await productDetailsPage.selectVariant(product.colour);
      await productDetailsPage.setQuantity(product.quantity);
      await productDetailsPage.addToBasket();
    }

    await shippingAddressPage.navigate();
    await shippingAddressPage.enterAddress(orderData.shippingAddress);
    await shippingAddressPage.selectShippingMethod("flatrate_flatrate");
    await shippingAddressPage.proceedToPaymentPage();
    await paymentPage.navigate();
    await paymentPage.selectHokodo();
    await paymentPage.hokodoCheckout.findCompany(orderData.buyer);
    await paymentPage.hokodoCheckout.selectPaymentMethod("invoice");
    await paymentPage.hokodoCheckout.acceptTermsAndConditions();
    const deferredPaymentResponse = await paymentPage.hokodoCheckout.createDeferredPayment();
    await checkoutSuccessPage.viewOrder();
    await adminLoginPage.navigate();
    await adminLoginPage.loginToAdmin();
    await adminHomePage.navigateToListOrdersPage();
    const orderDetails = await new HokodoAPI().viewOrder(deferredPaymentResponse.orderId, deferredPaymentResponse.token); // Order ID are updated async after DP is created in Magento. Delaying this call as late as possible to factor this in.
    await listOrdersPage.navigateToOrderPage(orderDetails.unique_id);
    await orderPage.navigateToShipOrderPage();
    await shipOrderPage.shipOrder();
    const deferredPaymentDetails = await new HokodoAPI().viewDeferredPayment(deferredPaymentResponse.deferredPaymentId, deferredPaymentResponse.token);
    expect(deferredPaymentDetails.status == "fulfilled")
  });

  test("Placing and fulfilling an Order for a Sole Trader without checking Credit Limit first", async ({
    createAccountPage,
    homePage,
    orderData,
    productDetailsPage,
    shippingAddressPage,
    paymentPage,
    checkoutSuccessPage,
    adminLoginPage,
    adminHomePage,
    listOrdersPage,
    orderPage,
    shipOrderPage,
    page,
  }) => {
    await createAccountPage.navigate();
    await createAccountPage.createAccount(orderData.buyer);

    for (const product of orderData.products) {
      await homePage.navigate();
      await homePage.addItemToBasket(product.name);
      await productDetailsPage.selectVariant(product.size);
      await productDetailsPage.selectVariant(product.colour);
      await productDetailsPage.setQuantity(product.quantity);
      await productDetailsPage.addToBasket();
    }

    await shippingAddressPage.navigate();
    await shippingAddressPage.enterAddress(orderData.shippingAddress);
    await shippingAddressPage.selectShippingMethod("flatrate_flatrate");
    await shippingAddressPage.proceedToPaymentPage();
    await paymentPage.navigate();
    await paymentPage.selectHokodo();
    await paymentPage.hokodoCheckout.findCompany(orderData.buyer);
    await paymentPage.hokodoCheckout.selectPaymentMethod("invoice");
    await paymentPage.hokodoCheckout.acceptTermsAndConditions();
    const deferredPaymentResponse = await paymentPage.hokodoCheckout.createDeferredPayment();
    await checkoutSuccessPage.viewOrder();
    await adminLoginPage.navigate();
    await adminLoginPage.loginToAdmin();
    await adminHomePage.navigateToListOrdersPage();
    const orderDetails = await new HokodoAPI().viewOrder(deferredPaymentResponse.orderId, deferredPaymentResponse.token); // Order ID are updated async after DP is created in Magento. Delaying this call as late as possible to factor this in.
    await listOrdersPage.navigateToOrderPage(orderDetails.unique_id);
    await orderPage.navigateToShipOrderPage();
    await shipOrderPage.shipOrder();
    const deferredPaymentDetails = await new HokodoAPI().viewDeferredPayment(deferredPaymentResponse.deferredPaymentId, deferredPaymentResponse.token);
    expect(deferredPaymentDetails.status == "fulfilled")
  });
});
