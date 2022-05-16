export class ProductDetailsPage {
    selectVarient(variant) {
        cy.get(`#product-options-wrapper .swatch-attribute [aria-label="${variant}"]`, { timeout: 60000 })
            .click();

        return this;
    }

    addToBasket() {
        cy.get('#product-addtocart-button').click();
        cy.get("[data-ui-id='message-success']")
    }
}