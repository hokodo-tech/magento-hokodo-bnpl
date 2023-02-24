import { Page } from "@playwright/test";
import CreditLimitsBanner from "./credit-limits-banner";

export default class HomePage {
    readonly page: Page;
    readonly url: string;
    readonly hokodoTopBanner: CreditLimitsBanner;

    constructor(page: Page) {
        this.page = page;
        this.hokodoTopBanner = new CreditLimitsBanner(page, "header .hokodo-marketing-banner-wrapper");
    }

    async navigate() {
        await this.page.goto("/");
    }

    async addItemToBasket(productName: string) {
        await this.page.locator(`[title='${productName}']`).click();
    }
}