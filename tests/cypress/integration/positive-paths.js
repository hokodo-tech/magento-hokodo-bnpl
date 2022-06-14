import { ShoppingCartPage } from "../support/page-objects/checkout/cart-page"
import { PaymentPage } from "../support/page-objects/checkout/payment-page"
import { ShippingAddressPage } from "../support/page-objects/checkout/shipping-address-page"
import { HokodoPaymentPage } from "../support/page-objects/hokodo-payment-page/hokodo-payment-page"
import { HomePage } from "../support/page-objects/home/home-page"
import { ProductDetailsPage } from "../support/page-objects/product-details/product-details-page"
import { AdminLoginPage } from "../support/page-objects/admin/login-page"
import { ListOrdersPage } from "../support/page-objects/admin/list-orders-page"

describe("Positive Paths", () => {
    it("places and fulfills an order", function() {
        // cy.intercept("https://*.segment.io/v*/t").as("segmentLog");

        cy.fixture("products/hero-hoodie").then((product) => {
            new HomePage()
                .navigate()
                .viewProduct(product.name);

            new ProductDetailsPage()
                .selectVarient(product.size)
                .selectVarient(product.colour)
                .addToBasket();
        });

        cy.generateNewUser('offered', 'accepted').then((user) => {
            const shippingAddressPage = new ShippingAddressPage();
            shippingAddressPage.navigate();
            shippingAddressPage.enterUserDetails(user.personalDetails);
            shippingAddressPage.selectShippingMethod("flatrate_flatrate");
            shippingAddressPage.enterAddress(user.address);
            shippingAddressPage.proceedToPaymentPage();

            // cy.wait("@segmentLog").then((log) => {
            //     expect(log.request.body).to.have.string("Initiation");
            //     expect(log.response.statusCode).to.eq(200);
            // });

            const paymentPage = new PaymentPage();

            paymentPage.selectHokodo()

            // cy.wait("@segmentLog").then((log) => {
            //     expect(log.request.body).to.have.string("Hokodo Selected");
            // });

            paymentPage.hokodo.findCompany(user.address);

            // cy.wait("@segmentLog").then((log) => {
            //     expect(log.request.body).to.have.string("Company Type");
            // });

            // cy.wait("@segmentLog").then((log) => {
            //     expect(log.request.body).to.have.string("Company Search");
            // });

            // cy.wait("@segmentLog").then((log) => {
            //     expect(log.request.body).to.have.string("Company Match");
            // });

            // cy.wait("@segmentLog").then((log) => {
            //     const request_body = JSON.parse(log.request.body);
            //     expect(request_body.event).to.equal("Eligibility Check")
            //     expect(request_body.properties.Eligible).to.be.true;
            // });

            paymentPage.hokodo.selectPlan({planName: "Pay in 30 days"});
            paymentPage.hokodo.agreeToTermsAndConditions();
            paymentPage.placeOrder();

            const hokodoPaymentPage = new HokodoPaymentPage();
            hokodoPaymentPage.payByInvoice();
            hokodoPaymentPage.acceptTermsAndConditions();
            hokodoPaymentPage.confirmPayment();

            new ShoppingCartPage()
                .verifyCartIsEmpty();

            new AdminLoginPage()
                .navigate()
                .login(Cypress.env('MAGENTO_ADMIN_USER'), Cypress.env('MAGENTO_ADMIN_PASSWORD'));

            new ListOrdersPage()
                 .navigate()
                 .viewOrderByCustomerName(user.personalDetails.firstName, user.personalDetails.lastName)
                 .getPaymentUpdateUntilStatus('Processing')
                 .navigateToShippingPage()
                 .waitForPageToLoad()
                 .submitShipment()
                 .confirmShipmentWasCreated();
        });
    })
})

