import { ProductDetailsPage } from "../product-details/product-details-page";

export class HomePage {
    navigate() {
        cy.visit('/');

        return this;
    }

    viewProduct(productName) {
        cy.get(`[title='${productName}']`).click()

        return new ProductDetailsPage()
    }
}