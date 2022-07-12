import { ShipOrderPage } from "../ship-order-page";
import { InvoiceTab } from "./invoice-tab";

export class ViewOrderPage {
    navigateToShippingPage() {
        cy.get("#order_ship", { timeout: 10000 }).click();

        return new ShipOrderPage();
    }

    /**
     * This will check whether or not the Order is in the desired status, and it not it will attempt to get a payment update from Hokodo
     * We need this because sometimes the test is so fast, the Hokodo API has not had chance to mark the Deferred Payment as Accepted so
     * when the test tries to ship the Order, it fails
     *
     * @param {string} desiredStatus The Status that you want the Order to be in
     * @param {number} attempt This method is called recursively. The attempt param is to ensure it bails out after a while in case the DP never gets updated
     * @return {ViewOrderPage} The current instance of this Page Object.
     */
    getPaymentUpdateUntilStatus(desiredStatus, attempt = 0) {
        if (attempt === 59)
            cy.fail(`The Order Status is not '${desiredStatus}', despite attempting to fetch a Payment Update from Hokodo for the last 10 minutes`);

        cy.get('#order_status')
            .should('not.be.empty')
            .invoke('text')
            .then((status) => {
                if (status !== desiredStatus) {
                    // the status isn't what we want, let's find the 'get payment update' button (if it exists) and click it
                    cy.get('.page-actions-buttons')
                        .then(($buttons) => {
                            if ($buttons.find('#get_review_payment_update').length) {
                                cy.wait(10000, {log: "Waiting for 10 seconds before requesting Payment Update from Hokodo"});
                                $buttons.find('#get_review_payment_update')[0].click()
                            } 
                            else {
                                cy.fail(`The current status is ${status} and the 'Get Payment Update' button is not available so we can't update it`)
                            }
                        })

                    this.getPaymentUpdateUntilStatus(desiredStatus, attempt + 1);
                }
            });

        return this;
    }

    confirmShipmentWasCreated() {
        cy.contains("The shipment has been created.").should('be.visible');

        return this;
    }

    confirmRefundWasCreated() {
        cy.contains("You created the credit memo.")
            .should('be.visible')
    }

    verifyOrderCannotBeShipped(){
        cy.get("#order_ship")
            .should('not.exist')
    }

    navigateToInvoicesTab() {
        cy.get('[data-ui-id="sales-order-tabs-tab-item-order-invoices"]')
            .click()

        return new InvoiceTab();
    }

}

