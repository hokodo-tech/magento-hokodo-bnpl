import { expect } from "@playwright/test";
import test from "../fixtures";
import { verifyAddressDetails, verifyHokodoOrder } from "../support/playwright-assertion-helpers";
import { getCaptureStatus, getHokodoIdsFromMagentoOrder } from "../support/playwright-test-helpers";
import { CompanyType } from "../support/types/Buyer";
import { MagentoOrderCaptureStatus } from "../support/types/MagentoOrder";

test.describe("Credit Limits", () => {
  test("Registered buyer viewing credit limits in the top banner", async ({
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
  }) => {
    
  });

  test("Registered buyer checking credit limits in the Product Details Page", async ({
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
    magentoApi
  }) => {
    
  });

  test("Hokodo banners are displayed to guest buyers", async ({
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
    magentoApi
  }) => {
    
  });
});