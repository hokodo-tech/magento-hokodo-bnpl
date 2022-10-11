import { Page } from "@playwright/test";
import { Buyer } from "../support/types/Buyer";

export default class CreateAccountPage {
    readonly page: Page;
    readonly url: string;

    constructor(page: Page) {
        this.page = page;
        this.url = "/customer/account/create";
    }

    async navigate() {
        await this.page.goto(this.url);
    }

    async createAccount({ firstName, lastName, email, password }: Buyer) {
        const registrationForm = await this.page.locator(".form-create-account");
        
        if (firstName) await registrationForm.locator("[name='firstname']").fill(firstName);
        if (lastName) await registrationForm.locator("[name='lastname']").fill(lastName);
        if (email ) await registrationForm.locator("[name='email']").fill(email);
        if (password) {
            await registrationForm.locator("[name='password']").fill(password);
            await registrationForm.locator("[name='password_confirmation']").fill(password);
        }                
        await registrationForm.locator("[type='submit']").click();
        await this.page.waitForSelector("[name='firstname']", { state: "detached" });
    }
}