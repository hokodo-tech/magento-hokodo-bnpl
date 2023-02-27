import { expect, Request } from "@playwright/test";
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

  test.only("Returning registered buyer should display their previous credit limit", async ({
    homePage,
    generateOrderData,
    createAccountPage,
    buyerLoginPage,
    page,
  }) => {
    const customerRequests: Array<Request> = [];

    // capture all hokodo/customer requests
    page.on('request', async (req) => {
      if (req.url().endsWith("rest/V1/hokodo/customer")) {
        customerRequests.push(req);
      }
    });

    const testOrderData = await generateOrderData(CompanyType.REGISTERED_COMPANY);
    
    // create an account and check your credit score
    await createAccountPage.navigate();
    await createAccountPage.createAccount(testOrderData.buyer);

    await homePage.navigate();

    await homePage.hokodoTopBanner.checkCreditLimit(testOrderData.buyer.companyName);
    
    // logout, then login again and return to the homepage
    await homePage.logout();
    
    await buyerLoginPage.navigate();
    await buyerLoginPage.login(testOrderData.buyer.email, testOrderData.buyer.password);

    await homePage.navigate();

    // wait for the request to get the Buyer's data (it should have cached this from the first time the Buyer was on the homepage)
    await page.waitForRequest(r => r.url().includes("rest/V1/hokodo/customer"));

    expect(await homePage.hokodoTopBanner.isDisplayed(), "The Hokodo Top Banner was not displayed").toBe(true);

    const firstCustomerRequest = customerRequests[0];
    const lastCustomerRequest = customerRequests.at(-1);

    // verify that there were two requests to get customer data and they both contained the same request and response
    expect(customerRequests.length, "Expected to see two requests to the 'rest/V1/hokodo/customer' endpoint").toBeGreaterThanOrEqual(2);
    expect(firstCustomerRequest.postData(), "The 'rest/V1/hokodo/customer' endpoint should have been called twice with the same data, as the Buyer is the same").toBe(lastCustomerRequest?.postData());
    expect(await((await firstCustomerRequest.response()))?.json(), "The 'rest/V1/hokodo/customer' endpoint should have responded twice with the same data, as the Buyer is the same").toStrictEqual(await((await lastCustomerRequest?.response()))?.json());
  });

  test("Handling logging in with different accounts at different companies", async ({
    homePage,
    generateOrderData,
    createAccountPage,
    page,
  }) => {
    const customerRequests: Array<Request> = [];

    // capture all hokodo/customer requests
    page.on('request', async (req) => {
      if (req.url().endsWith("rest/V1/hokodo/customer")) {
        customerRequests.push(req);
      }
    });

    // Buyer one create an account and check the credit score
    const firstBuyerOrder = await generateOrderData(CompanyType.REGISTERED_COMPANY);
        
    await createAccountPage.navigate();
    await createAccountPage.createAccount(firstBuyerOrder.buyer);

    await homePage.navigate();

    await homePage.hokodoTopBanner.checkCreditLimit(firstBuyerOrder.buyer.companyName);
    
    await homePage.logout();

    // Buyer two create an account and check the credit score
    const secondBuyerOrder = await generateOrderData(CompanyType.REGISTERED_COMPANY);
    secondBuyerOrder.buyer.companyName = "Tesco PLC"
    
    await createAccountPage.navigate();
    await createAccountPage.createAccount(secondBuyerOrder.buyer);

    await homePage.navigate();

    await homePage.hokodoTopBanner.checkCreditLimit(secondBuyerOrder.buyer.companyName); // buyer is from a different company

    // verify that there were two requests to get customer data and they both referenced different companies
    const firstCustomerRequest = customerRequests[0];
    const lastCustomerRequest = customerRequests.at(-1);
    
    expect(customerRequests, "Expected to see two requests to the 'rest/V1/hokodo/customer' endpoint").toHaveLength(2);
    expect(firstCustomerRequest.postDataJSON(), "The 'rest/V1/hokodo/customer' endpoint should have been called twice with different data, as the Buyer's company is not the same").not.toStrictEqual(lastCustomerRequest?.postDataJSON());
    expect(await((await firstCustomerRequest.response()))?.json(), "The 'rest/V1/hokodo/customer' endpoint should have responded twice with different data, as the Buyer is not the same").not.toStrictEqual(await((await lastCustomerRequest?.response()))?.json());
  });

  test("Handling logging in with different accounts but the same Company", async ({
    homePage,
    generateOrderData,
    createAccountPage,
    page,
  }) => {
    const customerRequests: Array<Request> = [];

    // capture all hokodo/customer requests
    page.on('request', async (req) => {
      if (req.url().endsWith("rest/V1/hokodo/customer")) {
        customerRequests.push(req);
      }
    });

    // Buyer one create an account and check the credit score
    const firstBuyerOrder = await generateOrderData(CompanyType.REGISTERED_COMPANY);
        
    await createAccountPage.navigate();
    await createAccountPage.createAccount(firstBuyerOrder.buyer);

    await homePage.navigate();

    await homePage.hokodoTopBanner.checkCreditLimit(firstBuyerOrder.buyer.companyName);
    
    await homePage.logout();

    // Buyer two create an account and check the credit score
    const secondBuyerOrder = await generateOrderData(CompanyType.REGISTERED_COMPANY);
    
    await createAccountPage.navigate();
    await createAccountPage.createAccount(secondBuyerOrder.buyer);

    await homePage.navigate();

    await homePage.hokodoTopBanner.checkCreditLimit(secondBuyerOrder.buyer.companyName); // buyer is from the same company

    // verify that there were two requests to get customer data and they both referenced different companies
    const firstCustomerRequest = customerRequests[0];
    const lastCustomerRequest = customerRequests.at(-1);
    
    expect(customerRequests, "Expected to see two requests to the 'rest/V1/hokodo/customer' endpoint").toHaveLength(2);
    expect(firstCustomerRequest.postDataJSON(), "The 'rest/V1/hokodo/customer' endpoint should have been called twice with the same data, as the Buyer's company is the same").toStrictEqual(lastCustomerRequest?.postDataJSON());
    expect(await((await firstCustomerRequest.response()))?.json(), "The 'rest/V1/hokodo/customer' endpoint should have responded twice with different data, as the Buyer is not the same").not.toStrictEqual(await((await lastCustomerRequest?.response()))?.json());
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

  test("Hokodo banners are displayed to guest buyers", async ({
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