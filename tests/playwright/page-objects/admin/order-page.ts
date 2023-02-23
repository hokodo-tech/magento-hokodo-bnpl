import { Page } from "@playwright/test";
import { elementExists } from "../../support/playwright-assertion-helpers";
import InvoicePage from "./invoice-page";

export default class OrderPage {
    readonly page: Page;

    constructor(page: Page) {
        this.page = page
    }

    async getPaymentUpdate() {
        const updateLocator = this.page.locator("text='Get Payment Update'");
        
        const needsUpdate = await updateLocator.isVisible();

        if (needsUpdate) {
            await updateLocator.click();
        }
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

    async captureInvoice() {
        await this.getPaymentUpdate();
        await this.page.locator("#order_invoice").click();
        new InvoicePage(this.page).captureInvoice();
    }

    async cancelOrder() {
        await this.getPaymentUpdate();
        await this.page.locator("#order-view-cancel-button").click();
        await this.page.locator(".action-accept").click();
        await this.page.locator("text='You canceled the order.'");
    }

    async navigateToShipOrderPage() {
        await this.page.locator("#order_ship").click();
    }

    async canShipOrder() {
        await this.page.locator(".page-actions-buttons").waitFor();
        return await this.page.locator("#order_ship").count() > 0;
    }

    async navigate(orderId: number) {
        await this.page.goto(`/admin/sales/order/view/order_id/${orderId}`);
    }
}