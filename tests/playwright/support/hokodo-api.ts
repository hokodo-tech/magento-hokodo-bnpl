import { APIRequestContext, expect, Page, request } from "@playwright/test";
import { DeferredPayment } from "./types/DeferredPayment";
import { HokodoOrder } from "./types/HokodoOrder";
import { HokodoOrganisation } from "./types/HokodoOrganisation";

export class HokodoAPI {
    page: Page;

    constructor(page: Page) {
        this.page = page;
    }

    async viewOrganisation(organisationId: string): Promise<HokodoOrganisation> {
        return this.fetchItem(`/v1/organisations/${organisationId}`);
    }

    async viewOrder(orderId: string): Promise<HokodoOrder> {
        return this.fetchItem(`/v1/payment/orders/${orderId}?expand=deferred_payment`);
    }

    async viewDeferredPayment(deferredPaymentId: string): Promise<DeferredPayment> {
        return this.fetchItem(`/v1/payment/deferred_payments/${deferredPaymentId}`);
    }

    async waitForDeferredPaymentToReachStatus(deferredPaymentId: string, desiredStatus: string): Promise<DeferredPayment> {
        let attemptsRemaining = 60;
        let deferredPayment = await this.viewDeferredPayment(deferredPaymentId);

        while (attemptsRemaining > 0) {
            if (deferredPayment.status.toLocaleLowerCase() === desiredStatus.toLocaleLowerCase()) {
                break;
            }

            await this.page.waitForTimeout(2000); // wait two seconds then try again
            attemptsRemaining -= 1;

            deferredPayment = await this.viewDeferredPayment(deferredPaymentId);
        }

        expect(deferredPayment.status.toLocaleLowerCase(), `Deferred Payment ${deferredPaymentId} never reached a status of ${desiredStatus} after 2 minutes`).toBe(desiredStatus.toLocaleLowerCase());

        return deferredPayment;
    }

    async fetchItem(path: string) {
        const context: APIRequestContext = await request.newContext({
            baseURL: process.env.HOKODO_API_BASE_URL,
            extraHTTPHeaders: {
                Authorization: `Token ${process.env.HOKODO_API_KEY}`,
            },
        });

        try {
            return await context.get(path).then(async (response) => {
                expect(response.status(), `Get ${path}`).toBe(200);
                
                return await response.json();
            });
        } finally {
            context.dispose();
        }
    }
}
