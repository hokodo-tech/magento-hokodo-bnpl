import { ViewInvoicePage } from "../view-invoice-page";

export class InvoiceTab {
    viewInvoice() {
        cy.contains(/^View$/)
            .click();
        
        return new ViewInvoicePage();
    }
}