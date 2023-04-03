import { Page } from "@playwright/test";
import { Buyer, CompanyType } from "../../support/types/Buyer";
import CheckoutSuccessPage from "./checkout-success-page";

export default class HokodoCheckout {
    readonly page: Page;

    constructor(page: Page) {
        this.page = page;
    }

    async selectBuyerType(buyerType: CompanyType) {
        await this.page.locator(`[for='${buyerType}']`).click();
    }
        
    getIframe() {
        return this.page.frameLocator(".hokodo-content-wrapper iframe").first();
    }

    async findRegisteredCompany(buyer: Buyer) {
        if (buyer.companyCountry) await this.page.locator("#country").selectOption(buyer.companyCountry);

        await this.page.locator("[for='registered-company']").click();
        
        if (buyer.companyName) {
            await this.page.locator("[aria-controls='companySearchListbox']").fill(buyer.companyName);
            await this.page.locator("#companySearchListbox").locator(`text="${buyer.companyName}"`).click();
        }

        await this.confirmCompany();
    }

    async selectAPaymentPlan() {
        const responseBody = await (await this.page.waitForResponse(r => r.url().includes("hokodo-request-offer"))).json();

        // if there's only one offer, it will be auto-selected
        if (responseBody.offer.offered_payment_plans.length === 1) {
            return 
        }
        
        const iframe = this.getIframe();

        await iframe.locator("[name='paymentOffers'] + [data-testid='customRadio']").first().click();
        await iframe.locator("[data-testid='selectedPaymentPlan'] button").click();
    }

    async confirmCompany() {
        await this.page.locator("#hokodoCompanySearch [type='submit']").click();
    }

    async setupSoleTrader(buyer: Buyer) {
        await this.selectBuyerType(CompanyType.SOLE_TRADER);
        await this.populateSoleTraderFields(buyer);
        await this.confirmCompany();
    }

    async populateSoleTraderFields(buyer: Buyer) {
        await this.page.locator("#trading_name").fill(buyer.companyName || "");
        await this.page.locator("#trading_address").fill(buyer.companyAddress?.address_line1 || "");
        await this.page.locator("#trading_address_city").fill(buyer.companyAddress?.city || "");
        await this.page.locator("#trading_address_postcode").fill(buyer.companyAddress?.postcode || "");
        await this.page.locator("#proprietor_name").fill((buyer.firstName + " " + buyer.lastName) || "");
        await this.page.locator("#date_of_birth").type(buyer.dateOfBirth || "");
        await this.page.locator("#proprietor_address_line1").fill(buyer.ownerAddress?.address_line1 || "");
        await this.page.locator("#proprietor_address_city").fill(buyer.ownerAddress?.city || "");
        await this.page.locator("#proprietor_address_postcode").fill(buyer.ownerAddress?.postcode || "");
    }

    async checkIfCreditIsDeclined() {
        await this.page.frame(".hokodo-content-wrapper iframe")
            ?.waitForSelector("text='Trade Credit Declined'", { state: "visible" });
    }

    async selectPaymentMethod(paymentMethod: string) {
        const iframe = this.getIframe();

        await iframe.locator(`[for="${paymentMethod}"] [data-testid='customRadio']`).click();
        await iframe.locator("text='Continue'").click();
    }

    async createDeferredPayment(): Promise<string> {
        await this.getIframe().locator("text='Confirm'").click();
        return await new CheckoutSuccessPage(this.page).extractOrderIncrementId();
    }
}
