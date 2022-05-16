export class ShippingAddressPage {
    navigate() {
        cy.visit('/checkout/#shipping');
    }

    enterUserDetails({ firstName, lastName, email }) {
        cy.get("#checkoutSteps [name='username']").type(email); // the #checkoutSteps prefix is because there are multiple username fields on the page
        cy.get("[name='firstname']").type(firstName);
        cy.get("[name='lastname']").type(lastName);
    }

    enterAddress({ lineOne, lineTwo, lineThree, state, city, countryCode, postCode, phoneNumber, companyName }) {
        if (companyName)
            cy.get("[name='company']").type(companyName);

        cy.get("[name='street[0]']").type(lineOne);

        if (lineTwo)
            cy.get("[name='street[1]']").type(lineTwo);

        if (lineThree)            
            cy.get("[name='street[2]']").type(lineThree);

        cy.get("[name='country_id']").select(countryCode);

        if (state)
            cy.get("[name='region']").type(state);

        cy.get("[name='city']").type(city);

        if (postCode)
            cy.get("[name='postcode']").type(postCode);

        cy.get("[name='telephone']").type(phoneNumber);
    }

    selectShippingMethod(shippingMethod) {
        cy.get(`[type='radio'][value='${shippingMethod}']`).click();
    }

    proceedToPaymentPage() {
        cy.get('button.continue').click();
    }
}