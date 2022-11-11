import { Locator, Page } from "@playwright/test";

export default class ListOrdersPage {
    readonly page: Page;

    constructor(page: Page) {
        this.page = page;
    }

    async navigateToOrderPage(orderId: string) {
        await this.page.locator(`text="${orderId}"`).click();
    }
}