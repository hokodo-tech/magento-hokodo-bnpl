import { Page } from "@playwright/test";

export default class ProductDetailsPage {

    readonly page: Page;
    readonly url: string;

    constructor(page: Page) {
        this.page = page;
    }

    async selectVariant(variant) {
        await this.page.locator(`#product-options-wrapper .swatch-attribute [aria-label="${variant}"]`)
            .click();

        return this;
    }

    async setQuantity(quantity: number) {
        await this.page.locator("[name='qty']").fill(quantity.toString());
    }

    async addToBasket() {
        await this.page.locator("#product-addtocart-button").click();
        await this.page.waitForSelector("[data-ui-id='message-success']");
    }
}