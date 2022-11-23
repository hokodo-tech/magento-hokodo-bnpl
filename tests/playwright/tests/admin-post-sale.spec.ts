import test from "../fixtures";
import { CompanyType, CreditStatus, FraudStatus } from "../support/types/Buyer";

test.describe("Post Sale Admin Actions", () => {
  test("Can't fulfil a pending review Deferred Payment", async ({
    createAccountPage,
    homePage,
    generateOrderData,
    productDetailsPage,
    shippingAddressPage,
    paymentPage,
    adminLoginPage,
    adminHomePage,
    listOrdersPage,
    orderPage,
    hokodoApi,
  }) => {
    const testOrderData = await generateOrderData(CompanyType.REGISTERED_COMPANY, { creditStatus: CreditStatus.OFFERED, fraudStatus: FraudStatus.PENDING_REVIEW});
    
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

    // find registered company
    await paymentPage.selectHokodo();
    await paymentPage.hokodoCheckout.findRegisteredCompany(testOrderData.buyer);

    // pay with Hokodo
    await paymentPage.hokodoCheckout.selectAPaymentPlan();
    await paymentPage.hokodoCheckout.selectPaymentMethod("invoice");
    await paymentPage.hokodoCheckout.acceptTermsAndConditions();
    const orderId = await paymentPage.hokodoCheckout.createDeferredPayment();
    
    await adminLoginPage.navigate();
    await adminLoginPage.loginToAdmin();
    await adminHomePage.navigateToListOrdersPage();

    // Order ID are updated async after DP is created in Magento. Delaying this call as late as possible to factor this in.
    const order = await hokodoApi.viewOrder(orderId);

    await listOrdersPage.navigateToOrderPage(order.unique_id);
    await orderPage.checkShipButtonIsNotVisible();
  });
});