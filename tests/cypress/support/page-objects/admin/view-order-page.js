import { ShipOrderPage } from "./ship-order-page";

export class ViewOrderPage {
    navigateToShippingPage() {
        cy.get("#order_ship").click();

        return new ShipOrderPage();
    }

    confirmShipmentWasCreated() {
        cy.contains("The shipment has been created.").should('be.visible');
    }
}