import { ViewOrderPage } from "./view-order-page";

export class ShipOrderPage {
    waitForPageToLoad() {
        cy.intercept("**/admin/mui/index/render/key/**").as("checkForAdminMessages"); // this is an async call that happens when the page loads. Nothing works until it's finished
        cy.wait("@checkForAdminMessages", { timeout: 10000 }); // so here we wait for it to complete

        return this;
    }

    submitShipment() {
        cy.contains("Submit Shipment").click();

        return new ViewOrderPage();
    }
}