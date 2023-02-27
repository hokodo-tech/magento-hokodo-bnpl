import { Page } from "@playwright/test";

export default class LoginPageBase {
    readonly page: Page;
    readonly url: string;

    constructor(page: Page, url: string) {
        this.page = page;
        this.url = url;
    }

    public async login(username?: string, password?: string): Promise<void> {
        const usernameSelector = "[name='login[username]']";
        await this.page.locator(usernameSelector).fill(username || "");
        await this.page.locator("[name='login[password]']").fill(password || "");

        await this.page.locator(usernameSelector).press("Enter");

        await this.page.waitForSelector(usernameSelector, { state: "detached" });
        await this.page.waitForSelector("text=Welcome, ", { state: "visible" });
    }

    public async navigate() {
        await this.page.goto(this.url);
    }
}