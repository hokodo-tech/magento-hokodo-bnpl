Cypress.Commands.add('generateNewUser', (paymentPlanStatus, fraudStatus) => {
    cy.fixture("address").then(($address) => {
        const user =  {
            address: $address,
            personalDetails: { 
                email: `test+${Date.now().toString().slice(-5)}_paymentplan_${paymentPlanStatus}_dp_fraud_${fraudStatus}@hokodo.co`, 
                firstName: "Cypress",
                lastName: Date.now() // ensure the Buyers's name is unique
            }            
        };

        if (user.personalDetails.email.split('@')[0].length > 64) {
            throw new Error("The first part of an email must not exceed 64 characters")
        } 
        
        return user
    });
});

Cypress.Commands.add("switchToFrame", (iframe) => {
    return cy
        .get(iframe)
        .its("0.contentDocument.body")
        .should("be.visible")
        .then(cy.wrap);
})