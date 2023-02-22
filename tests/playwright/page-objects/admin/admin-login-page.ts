import { Page } from "@playwright/test";
import LoginPageBase from "../login-page-base";

export default class AdminLoginPage extends LoginPageBase {
    constructor(page: Page) {
        super(page, "/admin");
    }

    public async login(): Promise<void> {
        super.login(process.env.MAGENTO_ADMIN_USER || "", process.env.MAGENTO_ADMIN_PASSWORD || "");
    }
}