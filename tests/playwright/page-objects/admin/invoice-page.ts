import { Page } from "@playwright/test";

export default class InvoicePage {
  readonly page: Page;

  constructor(page: Page) {
    this.page = page;
  }

  async captureInvoice() {
    await this.page.waitForRequest((r) =>
      r.url().includes("notification_area")
    );
    await this.page.waitForLoadState("networkidle");
    await this.page
      .locator("[name='invoice[capture_case]']")
      .selectOption("online");
    await this.page.locator('text="Submit Invoice"').click();
    await this.page.waitForSelector("text='The invoice has been created.'");
    await this.page.waitForRequest((r) =>
      r.url().includes("notification_area")
    );
  }
}
