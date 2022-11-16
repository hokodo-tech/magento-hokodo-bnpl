import { Page } from "@playwright/test";
import { Buyer, CompanyType } from "../../support/types/Buyer";

export default class HokodoCheckout {
    readonly page: Page;

    constructor(page: Page) {
        this.page = page;
    }

    getIframe() {
        return this.page.frameLocator(".hokodo-content-wrapper iframe").first();
    }

    async findCompany(buyer: Buyer) {
        if (buyer.companyCountry) await this.page.locator("#country").selectOption(buyer.companyCountry);

        if (buyer.companyType === CompanyType.REGISTERED_COMPANY) {
            await this.page.locator("[for='registered-company']").click();
            if (buyer.companyName) {
                await this.page.locator("[aria-controls='companySearchListbox']").fill(buyer.companyName);
                await this.page.locator("#companySearchListbox").locator(`text="${buyer.companyName}"`).click();
                await this.page.locator("#hokodoCompanySearch [type='submit']").click();
            }
        }
    }

    async checkIfCreditIsDeclined() {
        await this.page.frame(".hokodo-content-wrapper iframe")
            ?.waitForSelector("text='Trade Credit Declined'", { state: "visible" });
    }

    async selectPaymentMethod(paymentMethod: string) {
        const iframe = this.getIframe();
        await this.page.waitForRequest("https://h.online-metrix.net/**");
        await this.page.waitForTimeout(2000);
        await iframe.locator(`[for="${paymentMethod}"] [data-testid='customRadio']`).click();
        await iframe.locator("text='Continue'").click();
    }

    async acceptTermsAndConditions() {
        await this.getIframe()
            .locator("[data-testid='paymentConfirmation.form'] [data-testid='customCheckbox']")
            .click();
    }

    async createDeferredPayment() {
        const iframe = this.page.frameLocator(".hokodo-content-wrapper iframe").first();
        return await Promise.all([
            this.page.waitForResponse("**/v1/payment/deferred_payments"),
            iframe.locator("text='Confirm'").click(),
        ]).then(async (result) => {
            const response = await result[0].json();
            const requestHeaders = await result[0].request().allHeaders();
            return { orderId: response.order, deferredPaymentId: response.id, token: requestHeaders.authorization };
        });
    }
}
