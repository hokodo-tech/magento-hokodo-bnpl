import { APIRequestContext, request } from "@playwright/test";
import { DeferredPayment } from "./types/DeferredPayment";
import { HokodoOrder } from "./types/HokodoOrder";
import { HokodoOrganisation } from "./types/HokodoOrganisation";

export class HokodoAPI {
    async viewOrganisation(organisationId: string): Promise<HokodoOrganisation> {
        return this.fetchItem(`/v1/organisations/${organisationId}`);
    }

    async viewOrder(orderId: string): Promise<HokodoOrder> {
        return this.fetchItem(`/v1/payment/orders/${orderId}?expand=deferred_payment`);
    }

    async viewDeferredPayment(deferredPaymentId: string): Promise<DeferredPayment> {
        return this.fetchItem(`/v1/payment/deferred_payments/${deferredPaymentId}`);
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
                return await response.json();
            });
        } finally {
            context.dispose();
        }
    }
}
