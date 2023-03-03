import { Page } from "@playwright/test";

export default class LoginPageBase {
    readonly page: Page;
    readonly url: string;

    constructor(page: Page, url: string) {
        this.page = page;
        this.url = url;
    }

    protected async login(username?: string, password?: string): Promise<void> {
        await this.page.evaluate(() => window.localStorage.clear());
        const usernameSelector = "[name='login[username]']";
        await this.page.locator(usernameSelector).fill(username || "");
        await this.page.locator("[name='login[password]']").fill(password || "");

        await this.page.locator(usernameSelector).press("Enter");

        await this.page.waitForSelector(usernameSelector, { state: "detached" });
    }

    public async navigate() {
        await this.page.goto(this.url);
    }
}