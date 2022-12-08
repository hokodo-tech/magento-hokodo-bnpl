import { APIRequestContext, request } from "@playwright/test";
import { MagentoComments } from "./types/MagentoComment";
import { MagentoOrder } from "./types/MagentoOrder";

export class MagentoApi {
    context: APIRequestContext;
    token: string;
    baseUrl: string;

    constructor() {
        this.baseUrl = `${process.env.BASE_URL}/rest/default/V1`;
    }

    async setupContext() {
        this.context = await request.newContext({
            extraHTTPHeaders: {
                Authorization: `Bearer ${this.token}`,
                "Content-Type": "application/json; charset=utf-8",
            },
        });
    }

    async getComments(orderId: string): Promise<MagentoComments> {
        return await this.context.get(`${this.baseUrl}/orders/${orderId}/comments`).then(async (response) => await response.json());
    }

    async getBearerToken() {
        const context = await request.newContext();
        await context.post(`${this.baseUrl}/integration/admin/token`, { data: { "username": process.env.MAGENTO_ADMIN_USER, "password": process.env.MAGENTO_ADMIN_PASSWORD } })
        .then(async (response) => {
            this.token = await response.json();
        })
        context.dispose();
    }

    async getOrder(magentoOrderId: string): Promise<MagentoOrder> {
        return await this.context.get(`${this.baseUrl}/orders/${magentoOrderId}`).then(async (response) => await response.json());
    }

    async dispose() {
        this.context.dispose();
    }
}
