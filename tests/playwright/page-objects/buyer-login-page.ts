import { Page } from "@playwright/test";
import LoginPageBase from "./login-page-base";

export default class BuyerLoginPage extends LoginPageBase {
    constructor(page: Page) {
        super(page, "/customer/account/login");
    }

    public async login(_username?: string, _password?: string): Promise<void> {
        await this.page.waitForLoadState("networkidle");
        await super.login(_username, _password);
        await this.page.waitForSelector("text='Account Information'", { state: "attached" });
    }
}
