export class ShoppingCartPage {
    verifyCartIsEmpty () {
        cy.get('.cart-empty').should('be.visible'); 
    }
}