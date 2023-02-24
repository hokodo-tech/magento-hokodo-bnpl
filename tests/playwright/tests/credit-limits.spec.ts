import { expect } from "@playwright/test";
import test from "../fixtures";
import { CompanyType } from "../support/types/Buyer";

test.describe("Credit Limits", () => {
  test("First time registered buyer checking credit limits in the top banner", async ({
    homePage,
    generateOrderData,
    createAccountPage,
  }) => {
    const testOrderData = await generateOrderData(CompanyType.REGISTERED_COMPANY);

    await createAccountPage.navigate();
    await createAccountPage.createAccount(testOrderData.buyer);

    await homePage.navigate();

    await homePage.hokodoTopBanner.checkCreditLimit(testOrderData.buyer.companyName);

    expect(await homePage.hokodoTopBanner.canCheckCreditLimit(), "Can still check credit limits even though they're already known").toBe(false);
  });

  test("First time registered buyer checking credit limits in the Product Details Page", async ({
    productDetailsPage,
    generateOrderData,
    createAccountPage,
  }) => {
    const testOrderData = await generateOrderData(CompanyType.REGISTERED_COMPANY);

    await createAccountPage.navigate();
    await createAccountPage.createAccount(testOrderData.buyer);

    await productDetailsPage.navigate(testOrderData.products[0].name);

    await productDetailsPage.hokodoMarketing.checkCreditLimit(testOrderData.buyer.companyName);

    expect(await productDetailsPage.hokodoMarketing.canCheckCreditLimit(), "Can still check credit limits even though they're already known").toBe(false);
  });

  test.only("Hokodo banners are displayed to guest buyers", async ({
    homePage,
    productDetailsPage,
    generateOrderData
  }) => {
    const testOrderData = await generateOrderData(CompanyType.REGISTERED_COMPANY);

    await homePage.navigate();

    expect(await homePage.hokodoTopBanner.canCheckCreditLimit(), "Can check credit limit even though it's a guest buyer").toBe(false);

    await productDetailsPage.navigate(testOrderData.products[0].name);

    expect(await productDetailsPage.hokodoMarketing.canCheckCreditLimit(), "Can check credit limit even though it's a guest buyer").toBe(false);
  });
});