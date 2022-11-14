import { APIRequestContext, request } from "@playwright/test";

export class HokodoAPI {

    async viewOrder(orderId: string, authHeader: string) {

        const context: APIRequestContext = await request.newContext({
            baseURL: process.env.HOKODO_API_BASE_URL,
            extraHTTPHeaders: {
                Authorization: authHeader,
            },
        });

        try {
            return await context.get(`/v1/payment/orders/${orderId}`)
                .then(async (response) => {
                    return await response.json();
                });
        }
        finally {
            context.dispose();
        }
    }

    async viewDeferredPayment(deferredPaymentId: string, authHeader: string) {
        const context: APIRequestContext = await request.newContext({
            baseURL: process.env.HOKODO_API_BASE_URL,
            extraHTTPHeaders: {
                Authorization: authHeader,
            },
        });

        try {
            return await context.get(`/v1/payment/deferred_payments/${deferredPaymentId}`)
                .then(async (response) => {
                    return await response.json();
                });
        }
        finally {
            context.dispose();
        }
    }
}
