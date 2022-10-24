import { Page } from "@playwright/test";

export default class CheckoutSuccessPage {
    readonly page: Page;

    constructor(page: Page) {
        this.page = page;
    }

    async viewOrder() {
        await this.page.locator("a.order-number").click({ timeout: 60000 });
    }
}
