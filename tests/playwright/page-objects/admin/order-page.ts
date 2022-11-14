import { Locator, Page } from "@playwright/test";

export default class OrderPage {
    readonly page: Page;

    constructor(page: Page) {
        this.page = page
    }

    async navigateToShipOrderPage() {
        await this.page.locator('text="Ship"').click();
    }

}