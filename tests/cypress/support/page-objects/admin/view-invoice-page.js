import { ViewCreditMemoPage } from "./view-credit-memo"

export class ViewInvoicePage {
    captureInvoice() {
        cy.get('#capture')
            .click()

        return this;
    }
    createCreditMemo() {
        cy.get('#credit-memo')
            .click()
        
        return new ViewCreditMemoPage; 
    }

}