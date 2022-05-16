export class PlanSelection {
    findCompany({ companyName }) {
        cy.intercept("**/request-new-hokodo-offer").as("requestNewOfferRequest");
        cy.get("label[for='registeredCompany']").click();
        cy.get(".admin__action-multiselect-search").type(companyName, {force: true });
        cy.get(".company-information button").click();
        cy.get(".search-autocomplete-results", { timeout: 10000 }).contains(companyName).click();
        cy.wait("@requestNewOfferRequest", { timeout: 10000 });
    }

    selectPlan({ planName }) {
        cy.get(".payment-plan-list").contains(planName).click();
    }

    agreeToTermsAndConditions() {
        cy.window()
            .then((win) => {
                if (win.checkoutConfig.checkoutAgreements.isEnabled) {
                    cy.get("#agreement_hokodo_deferred_payment_1").click();
                }
            });
        
    }
}