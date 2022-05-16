export class AdminLoginPage {
    navigate() {
        cy.visit('/admin');
    
        return this;
    }

    login(username, password) {
        cy.get("#username").type(username, { log: false });
        cy.get("#login").type(password, { log: false });
        cy.get("#login").type("{enter}")
    }
}