import { Page } from "@playwright/test";

export default class CreditLimitsBanner {


    readonly page: Page;
    readonly parentElementSelector: string;

    constructor(page: Page, parentElementSelector: string) {
        this.page = page;
        this.parentElementSelector = parentElementSelector;
    }

    async checkCreditLimit(companyName?: string) : Promise<void> {
        await this.page.locator(this.parentElementSelector).locator("text='Check your available credit'").click();
        await this.page.locator("text='See your credit limit'").click();
        await this.page.locator("[aria-controls='companySearchListbox']").fill(companyName || "");
        await this.page.locator("#companySearchListbox").locator(`text="${companyName}"`).click();
        await this.page.locator("[data-testid='marketing-element-credit-limit-modal'] [data-testid='companySearch-submitButton']").click();
        await this.page.locator("text='Continue shopping'").click();
        await this.page.waitForSelector("text='Continue shopping'", { state: "detached" });
    }

    async canCheckCreditLimit() : Promise<boolean> {
        await this.page.waitForSelector(this.parentElementSelector);

        return await this.page.locator(this.parentElementSelector).locator("text='Check your available credit'").isVisible();
    }

    async isDisplayed() {
        return await 
            (await this.page.waitForSelector(this.parentElementSelector))
            .waitForSelector("[data-testid='marketing-element-container']")
            .then(x => true)
            .catch(x => false);
    }
}
