import '../support/commands'

Cypress.on("uncaught:exception", (err, runnable) => {
    // this is purely to ignore errors from recaptcha
    // TODO: replace with something more explicit
    return false
});
