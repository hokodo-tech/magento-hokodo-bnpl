import { ViewOrderPage } from "./view-order-page/information-tab";

export class ListOrdersPage {
    navigate() {
        cy.visit('/admin/sales/order/index');
    
        return this;
    }

    viewOrderByCustomerName(firstName, lastName) {
        cy.contains(`${firstName} ${lastName}`).click();

        return new ViewOrderPage();
    }
}