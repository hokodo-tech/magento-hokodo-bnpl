import { Locator, Page } from "@playwright/test";

export default class ShipOrderPage {
    readonly page: Page;

    constructor(page: Page) {
        this.page = page
    }

    async shipOrder() {
        await this.page.waitForResponse("**/admin/order_shipment/new/order_id/**");
        await this.page.locator('text="Submit Shipment"').click();
    }
}