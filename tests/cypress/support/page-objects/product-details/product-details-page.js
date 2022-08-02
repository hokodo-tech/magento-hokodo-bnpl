export class ProductDetailsPage {
    selectVarient(variant) {
        cy.get(`#product-options-wrapper .swatch-attribute [aria-label="${variant}"]`, { timeout: 60000 })
            .click();

        return this;
    }

    addToBasket() {
        cy.intercept("**/checkout/cart/add/uenc/**/product/**/").as("addToCart");
        cy.get('#product-addtocart-button').click();
        cy.wait("@addToCart", { timeout: 30000 });
    }
}