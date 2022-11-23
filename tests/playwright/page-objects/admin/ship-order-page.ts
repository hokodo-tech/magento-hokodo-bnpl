import { Page } from "@playwright/test";

export default class ShipOrderPage {
    readonly page: Page;

    constructor(page: Page) {
        this.page = page
    }

    async shipOrder() {
        // the "Submit Shipment" button doesn't always work if notifications haven't loaded. So wait for that first
        await this.page.waitForRequest(r => r.url().includes("notification_area"));
        await this.page.waitForLoadState("networkidle");
        await this.page.locator('text="Submit Shipment"').click();
        
        await this.page.waitForSelector("text='The shipment has been created.'");
    }
}