export class HokodoPaymentPage {

    selectPaymentMethod(paymentMethod) {
        cy.get(`label[for="${paymentMethod}"]`).click();
    }

    payByInvoice() {
        cy.wait(5000);
        this.selectPaymentMethod("invoice");
        this.useThisPaymentMethod();
    }

    useThisPaymentMethod() {
        cy.contains('button', 'Continue').click();
    }

    acceptTermsAndConditions() {
        cy.get("#terms").check({ force: true });
    }

    confirmPayment() {
        cy.intercept("**/v1/payment/deferred_payments").as("createDeferredPayment");
        cy.get("[data-testid='paymentConfirmation.submitButton']").click();
        cy.wait("@createDeferredPayment", { timeout: 20000 });
    }
}