import { Page } from "@playwright/test";
import { isLoggedIn } from "../../support/playwright-test-helpers";
import { Address } from "../../support/types/Address";
import { Buyer } from "../../support/types/Buyer";
import { OrderData } from "../../support/types/OrderData";

export default class ShippingAddressPage {
  readonly page: Page;
  readonly url: string;

  constructor(page: Page) {
    this.page = page;
  }

  async navigate() {
    await this.page.goto("/checkout/#shipping");
  }

  async setupNewShippingAddress(
    testOrderData: OrderData,
    shippingMethod: string
  ) {
    await this.navigate();

    if ((await isLoggedIn(this.page)) === false) {
      await this.enterBuyerDetails(testOrderData.buyer);
    }

    await this.enterAddress(testOrderData.shippingAddress);
    await this.selectShippingMethod(shippingMethod);
    await this.saveShippingDetails();
  }

  async enterBuyerDetails({ firstName, lastName, email }: Buyer) {
    if (email)
      await this.page.locator("#checkoutSteps [name='username']").fill(email); // the #checkoutSteps prefix is because there are multiple username fields on the page
    if (firstName)
      await this.page.locator("[name='firstname']").fill(firstName);
    if (lastName) await this.page.locator("[name='lastname']").fill(lastName);
  }

  async enterAddress(address: Address) {
    await this.page
      .locator("[name='company']")
      .fill(address.company_name, { timeout: 60000 });
    await this.page.locator("[name='street[0]']").fill(address.address_line1);
    await this.page.locator("[name='street[1]']").fill(address.address_line2);
    await this.page
      .locator("[name='country_id']")
      .selectOption(address.country);
    await this.page.locator("[name='region']").fill(address.region);
    await this.page.locator("[name='city']").fill(address.city);

    // entering the postcode triggers a call to get shipping methods
    await Promise.all([
      this.page.waitForResponse("**/estimate-shipping-methods"),
      this.page.locator("[name='postcode']").type(address.postcode),
    ]);

    await this.page.locator("[name='telephone']").type(address.phone);
  }

  async selectShippingMethod(shippingMethod: string) {
    await this.page
      .locator(`[type='radio'][value='${shippingMethod}']`)
      .click();
  }

  async saveShippingDetails() {
    await this.page.locator("button.continue").click();
    await this.page.waitForSelector("#checkout-step-shipping", {
      state: "hidden",
      timeout: 60000,
    });
    await this.page.waitForSelector(".loading-mask", {
      state: "hidden",
    });
  }
}
