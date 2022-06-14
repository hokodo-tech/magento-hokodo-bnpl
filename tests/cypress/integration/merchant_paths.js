import { ShoppingCartPage } from "../support/page-objects/checkout/cart-page"
import { PaymentPage } from "../support/page-objects/checkout/payment-page"
import { ShippingAddressPage } from "../support/page-objects/checkout/shipping-address-page"
import { HokodoPaymentPage } from "../support/page-objects/hokodo-payment-page/hokodo-payment-page"
import { HomePage } from "../support/page-objects/home/home-page"
import { ProductDetailsPage } from "../support/page-objects/product-details/product-details-page"
import { AdminLoginPage } from "../support/page-objects/admin/login-page"
import { ListOrdersPage } from "../support/page-objects/admin/list-orders-page"

describe("Merchant paths", () => {
    it("can't ship an order that is pending review", function() {

        cy.fixture("products/hero-hoodie").then((product) => {
            new HomePage()
                .navigate()
                .viewProduct(product.name);

            new ProductDetailsPage()
                .selectVarient(product.size)
                .selectVarient(product.colour)
                .addToBasket();
        });

        cy.generateNewUser('offered', 'pending_review').then((user) => {
            const shippingAddressPage = new ShippingAddressPage();
            shippingAddressPage.navigate();
            shippingAddressPage.enterUserDetails(user.personalDetails);
            shippingAddressPage.selectShippingMethod("flatrate_flatrate");
            shippingAddressPage.enterAddress(user.address);
            shippingAddressPage.proceedToPaymentPage();

            const paymentPage = new PaymentPage();

            paymentPage.selectHokodo()

            paymentPage.hokodo.findCompany(user.address);

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
                 .getPaymentUpdateUntilStatus('Payment Review')
                 .verifyOrderCannotBeShipped();
        }); 
    })

    it("can't ship an order that is customer action required", function() {

        cy.fixture("products/hero-hoodie").then((product) => {
            new HomePage()
                .navigate()
                .viewProduct(product.name);

            new ProductDetailsPage()
                .selectVarient(product.size)
                .selectVarient(product.colour)
                .addToBasket();
        });

        cy.generateNewUser('offered', 'customer_action_required').then((user) => {
            const shippingAddressPage = new ShippingAddressPage();
            shippingAddressPage.navigate();
            shippingAddressPage.enterUserDetails(user.personalDetails);
            shippingAddressPage.selectShippingMethod("flatrate_flatrate");
            shippingAddressPage.enterAddress(user.address);
            shippingAddressPage.proceedToPaymentPage();

            const paymentPage = new PaymentPage();

            paymentPage.selectHokodo()

            paymentPage.hokodo.findCompany(user.address);

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
                 .getPaymentUpdateUntilStatus('Payment Review')
                 .verifyOrderCannotBeShipped();
        }); 
    })
})
