import { Page } from "@playwright/test";
import { MagentoApi } from "../../support/magento-api";
import { elementExists } from "../../support/playwright-assertion-helpers";
import InvoicePage from "./invoice-page";

export default class OrderPage {
    readonly page: Page;

    constructor(page: Page) {
        this.page = page
    }

    async waitForDeferredPaymentToBeAccepted() {
        let attemptsRemaining = 150;

        // wait for the admin notifications. This allows the page time to load
        await this.page.waitForLoadState("networkidle");

        // check every 2 seconds to see if the order is ready to ship yet.
        // the maximum wait time could be up to five minutes as, if there is a queue of async tasks to do in Contorta, that's how long it could take
        // for a DP to be accepted by the fraud engine
        while (await elementExists(this.page, "#get_review_payment_update") && attemptsRemaining > 0) {
            await this.page.locator("#get_review_payment_update").click();
            await this.page.waitForTimeout(2000);
            attemptsRemaining--;
        }
    }

    async captureInvoice(orderId: string, magentoApi: MagentoApi) {
        await this.waitForDeferredPaymentToBeAccepted();

        const comments = await magentoApi.getComments(orderId);

        if(comments.items.find(x => x.comment.includes("Captured amount"))) {
            return; // the invoice has already been captured
        } else {
            await this.page.locator("#order_invoice").click();
            new InvoicePage(this.page).captureInvoice();
        }
    }

    async navigateToShipOrderPage() {
        await this.page.locator("#order_ship").click();
    }

    async checkShipButtonIsNotVisible() {
        await this.page.waitForSelector("#order_ship", { state: "hidden" });
    }
}