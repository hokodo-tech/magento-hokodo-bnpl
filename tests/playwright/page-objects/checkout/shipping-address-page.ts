import { Page } from "@playwright/test";
import { Address } from "../../support/types/Address";
import { Buyer } from "../../support/types/Buyer";

export default class ShippingAddressPage {
    readonly page: Page;
    readonly url: string;

    constructor(page: Page) {
        this.page = page;
    }

    async navigate() {
        await this.page.goto("/checkout/#shipping");
    }

    async enterBuyerDetails({ firstName, lastName, email }: Buyer) {
        if (email) await this.page.locator("#checkoutSteps [name='username']").fill(email); // the #checkoutSteps prefix is because there are multiple username fields on the page
        if (firstName) await this.page.locator("[name='firstname']").fill(firstName);
        if (lastName) await this.page.locator("[name='lastname']").fill(lastName);
    }

    async enterAddress({
        lineOne,
        lineTwo,
        lineThree,
        state,
        city,
        countryCode,
        postCode,
        phoneNumber,
        companyName
    } : Address) {
        if (companyName) await this.page.locator("[name='company']").type(companyName);

        await this.page.locator("[name='street[0]']").type(lineOne);

        if (lineTwo) await this.page.locator("[name='street[1]']").type(lineTwo);
        if (lineThree) await this.page.locator("[name='street[2]']").type(lineThree);

        await this.page.locator("[name='country_id']").selectOption(countryCode);
        
        await this.page.locator("[name='region']").type(state);
        await this.page.locator("[name='city']").type(city);

        await Promise.all([
            this.page.waitForResponse("**/estimate-shipping-methods"),
            this.page.locator("[name='postcode']").type(postCode)
        ]);
        
        await this.page.locator("[name='telephone']").type(phoneNumber);
    }

    async selectShippingMethod(shippingMethod: string) {
        await this.page.locator(`[type='radio'][value='${shippingMethod}']`).click();
    }

    async proceedToPaymentPage() {
        await this.page.locator('button.continue').click();
        await this.page.waitForSelector('#checkout-step-shipping', { state: "hidden", timeout: 60000 });
    }
}