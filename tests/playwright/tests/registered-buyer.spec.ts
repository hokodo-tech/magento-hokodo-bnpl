import { expect } from "@playwright/test";
import test from "../fixtures";
import { HokodoAPI } from "../support/hokodo-api";

test.describe("Full end-to-end", () => {

    test.beforeEach(async ({ page }) => {
      
    });
  
    test("Creating a Deferred Payment", async ({
      createAccountPage,
      homePage,
      orderData,
      productDetailsPage,
      shippingAddressPage,
      paymentPage,
      checkoutSuccessPage,
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
      await paymentPage.hokodoCheckout.createDeferredPayment();
      await checkoutSuccessPage.viewOrder();
    });
  });