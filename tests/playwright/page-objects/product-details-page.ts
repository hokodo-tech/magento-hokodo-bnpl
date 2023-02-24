import { Page } from "@playwright/test";
import CreditLimitsBanner from "./credit-limits-banner";

export default class ProductDetailsPage {

    readonly page: Page;
    readonly url: string;
    readonly hokodoMarketing: CreditLimitsBanner;

    constructor(page: Page) {
        this.page = page;
        this.hokodoMarketing = new CreditLimitsBanner(page, ".hokodo-marketing-banner-wrapper.product-banner");
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
        return await Promise.all([
            this.page.locator("#product-addtocart-button").click(),
            this.page.waitForResponse("**/checkout/cart/add/**") // wait for the item to be added
          ]);
    }

    async navigate(productName: string) {
        await this.page.goto(`/${productName.toLowerCase().replace(" ", "-")}.html`);
    }
}