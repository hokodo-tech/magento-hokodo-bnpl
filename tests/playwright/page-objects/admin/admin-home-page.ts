import { Locator, Page } from "@playwright/test";

export default class AdminHomePage {
    readonly page: Page;

    constructor(page: Page) {
        this.page = page;
    }

    async navigateToListOrdersPage() {
        await this.page.goto("/admin/sales/order/index/");
    }
}