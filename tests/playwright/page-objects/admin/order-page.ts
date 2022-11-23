import { Page } from "@playwright/test";

export default class OrderPage {
    readonly page: Page;

    constructor(page: Page) {
        this.page = page
    }

    async navigateToShipOrderPage() {
        let attemptsRemaining = 150;

        // wait for the admin notifications. This allows the page time to load
        await this.page.waitForLoadState("networkidle");

        // check every 2 seconds to see if the order is ready to ship yet.
        // the maximum wait time could be up to five minutes as, if there is a queue of async tasks to do in Contorta, that's how long it could take
        // for a DP to be accepted by the fraud engine
        while (await this.page.locator("#get_review_payment_update").count() > 0 && attemptsRemaining > 0) {
            await this.page.locator("#get_review_payment_update").click();
            await this.page.waitForTimeout(2000);
            attemptsRemaining--;
        }

        await this.page.locator("#order_ship").click();
    }

    async checkShipButtonIsNotVisible() {
        await this.page.waitForSelector("#order_ship", { state: "hidden" });
    }

}