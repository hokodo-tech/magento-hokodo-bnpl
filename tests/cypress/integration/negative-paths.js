import { ShoppingCartPage } from "../support/page-objects/checkout/cart-page"
import { PaymentPage } from "../support/page-objects/checkout/payment-page"
import { ShippingAddressPage } from "../support/page-objects/checkout/shipping-address-page"
import { HokodoPaymentPage } from "../support/page-objects/hokodo-payment-page/hokodo-payment-page"
import { HomePage } from "../support/page-objects/home/home-page"
import { ProductDetailsPage } from "../support/page-objects/product-details/product-details-page"
import { AdminLoginPage } from "../support/page-objects/admin/login-page"
import { ListOrdersPage } from "../support/page-objects/admin/list-orders-page"

describe("Negative Paths", () => {
    it("informs the buyer they are declined for credit", function() {

        cy.fixture("products/hero-hoodie").then((product) => {
            new HomePage()
                .navigate()
                .viewProduct(product.name);

            new ProductDetailsPage()
                .selectVarient(product.size)
                .selectVarient(product.colour)
                .addToBasket();
        });

        cy.generateNewUser('declined', 'accepted').then((user) => {
            const shippingAddressPage = new ShippingAddressPage();
            shippingAddressPage.navigate();
            shippingAddressPage.enterUserDetails(user.personalDetails);
            shippingAddressPage.selectShippingMethod("flatrate_flatrate");
            shippingAddressPage.enterAddress(user.address);
            shippingAddressPage.proceedToPaymentPage();

            const paymentPage = new PaymentPage();

            paymentPage.selectHokodo();

            paymentPage.hokodo.findCompany(user.address);

            paymentPage.hokodo.verifyPaymentPlanDeclined();

        }); 
    })
})
