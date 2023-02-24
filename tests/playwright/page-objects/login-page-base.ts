import { Page } from "@playwright/test";

export default class LoginPageBase {
    readonly page: Page;
    readonly url: string;

    constructor(page: Page, url: string) {
        this.page = page;
        this.url = url;
    }

    public async login(username?, password?): Promise<void> {
        await this.page.locator("[name='login[username]']").fill(username || "");
        await this.page.locator("[name='login[password]']").fill(password || "");

        await this.page.locator(".login.primary").click();
    }

    public async navigate() {
        await this.page.goto(this.url);
    }
}