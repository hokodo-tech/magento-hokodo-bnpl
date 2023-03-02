import { Page } from "@playwright/test";

export default class InvoicePage {
    readonly page: Page;

    constructor(page: Page) {
        this.page = page
    }

    async captureInvoice() {
        await this.page.locator("[name='invoice[capture_case]']").selectOption("online");
        await this.page.locator('text="Submit Invoice"').click();
        await this.page.waitForSelector("text='The invoice has been created.'");
    }
}