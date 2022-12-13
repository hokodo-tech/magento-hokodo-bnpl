import test from "../fixtures";
import { BuyerStatus, CompanyType, CreditStatus, FraudStatus } from "../support/types/Buyer";

test.describe("Credit is declined", () => {
  test("Can't place an order with Hokodo when declined for credit", async ({
    homePage,
    generateOrderData,
    productDetailsPage,
    shippingAddressPage,
    paymentPage,
  }) => {
    const buyerStatus: BuyerStatus = {
      creditStatus: CreditStatus.DECLINED,
      fraudStatus: FraudStatus.ACCEPTED
    };

    const testOrderData = await generateOrderData(CompanyType.REGISTERED_COMPANY, buyerStatus);

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

    // verify that the order doesn't qualify for BNPL
    await paymentPage.navigate();
    await paymentPage.selectHokodo();
    await paymentPage.hokodoCheckout.findRegisteredCompany(testOrderData.buyer);
    await paymentPage.hokodoCheckout.checkIfCreditIsDeclined();
  })
});