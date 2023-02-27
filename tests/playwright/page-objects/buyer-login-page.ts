import { Page } from "@playwright/test";
import LoginPageBase from "./login-page-base";

export default class BuyerLoginPage extends LoginPageBase {
    constructor(page: Page) {
        super(page, "/customer/account/login");
    }
}