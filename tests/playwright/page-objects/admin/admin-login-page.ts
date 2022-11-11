import { Locator, Page } from "@playwright/test";

export default class AdminLoginPage {
    readonly url: string;
    readonly page: Page;

    constructor(page: Page) {
        this.page = page;
        this.url = "/admin";
    }

    async navigate() {
        await this.page.goto(this.url);
    }

    async loginToAdmin() {
        await this.page.locator("[name='login[username]']").fill(process.env.MAGENTO_ADMIN_USER);
        await this.page.locator("[name='login[password]']").fill(process.env.MAGENTO_ADMIN_PASSWORD);
        await this.page.locator("text='Sign in'").click();
    }
}