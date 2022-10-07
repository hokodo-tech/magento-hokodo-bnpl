import { Page } from "@playwright/test";

export default class HomePage {
    readonly page: Page;
    readonly url: string;

    constructor(page: Page) {
        this.page = page;
    }

    async navigate() {
        await this.page.goto("/");
    }

    async addItemToBasket(productName: string) {
        await this.page.locator(`[title='${productName}']`).click();
    }
}