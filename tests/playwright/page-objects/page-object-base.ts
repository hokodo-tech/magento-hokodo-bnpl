import { Page } from "@playwright/test";
import CreditLimitsBanner from "./credit-limits-banner";

export default class PageObjectBase {
    protected readonly page: Page;
    readonly url: string;
    readonly hokodoTopBanner: CreditLimitsBanner;

    constructor(page: Page) {
        this.page = page;
        this.hokodoTopBanner = new CreditLimitsBanner(page, "header .hokodo-marketing-banner-wrapper");
    }

    async logout() {
        await this.page.locator("header [data-action='customer-menu-toggle']").click();
        await this.page.locator("header .customer-menu .authorization-link").click();
    }
}