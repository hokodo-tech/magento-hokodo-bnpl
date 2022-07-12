import { ViewOrderPage } from "./view-order-page/information-tab";

export class ViewCreditMemoPage {
    submitRefund() {
        cy.get('.order-totals-actions')
            .contains(/^Refund$/)
            .click()
        return new ViewOrderPage;
    }
}