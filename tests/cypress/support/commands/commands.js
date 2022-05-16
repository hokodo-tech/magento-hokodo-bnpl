Cypress.Commands.add('generateNewUser', () => {
    cy.fixture("address").then(($address) => {
        return {
            address: $address,
            personalDetails: {
                email: `test+${Date.now()}_paymentplan_offered_dp_fraud_accepted@hokodo.co`, // this email pattern will ensure the deferred payment is created without needing a manual review
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