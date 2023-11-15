import { Page } from "@playwright/test";
import { MagentoBasketDetails } from "../../support/types/MagentoOrder";
import HokodoCheckout from "./hokodo-checkout";

export default class PaymentPage {
  readonly page: Page;
  readonly hokodoCheckout: HokodoCheckout;

  constructor(page: Page) {
    this.page = page;
    this.hokodoCheckout = new HokodoCheckout(this.page);
  }

  async navigate() {
    await this.page.goto("/checkout/#payment");
  }

  async selectHokodo() {
    await this.page.locator("#hokodo_bnpl").click();
  }

  async getBasketDetails(): Promise<MagentoBasketDetails> {
    await this.page.reload();
    const response = await this.page.waitForResponse(
      "**/payment-information**"
    );
    return await response.json();
  }
}
