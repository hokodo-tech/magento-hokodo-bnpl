import { Page } from "@playwright/test";
import CreditLimitsBanner from "./credit-limits-banner";
import PageObjectBase from "./page-object-base";

export default class HomePage extends PageObjectBase {
    
    readonly page: Page;
    
    constructor(page: Page) {
        super(page);
        this.page = page;
    }

    async navigate() {
        await this.page.goto("/");
    }

    async addItemToBasket(productName: string) {
        await this.page.locator(`[title='${productName}']`).click();
    }
}