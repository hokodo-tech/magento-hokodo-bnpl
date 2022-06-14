Cypress.Commands.add('generateNewUser', (paymentPlanStatus, fraudStatus) => {
    cy.fixture("address").then(($address) => {
        return {
            address: $address,
            personalDetails: {
                email: `test+${Date.now()}_paymentplan_${paymentPlanStatus}_dp_fraud_${fraudStatus}@hokodo.co`, // this email pattern will ensure the deferred payment is created without needing a manual review
                firstName: "Cypress",
                lastName: Date.now() // ensure the Buyers's name is unique
            }            
        };
    });
});

Cypress.Commands.add("switchToFrame", (iframe) => {
    return cy
        .get(iframe)
        .its("0.contentDocument.body")
        .should("be.visible")
        .then(cy.wrap);
})