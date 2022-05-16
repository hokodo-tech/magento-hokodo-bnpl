import { PlanSelection } from "./hokodo/plan-selection";

export class PaymentPage {
    constructor() {
        this.hokodo = new PlanSelection()
    }

    navigate() {
        cy.visit('/checkout/#payment');
    }

    selectHokodo() {
        cy.get("#hokodo_bnpl").click();
    }

    placeOrder() {
        cy.get(".hokodo-gateway .actions-toolbar button.primary").click();
    }
}
