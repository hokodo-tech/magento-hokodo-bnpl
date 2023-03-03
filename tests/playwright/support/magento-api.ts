import { APIRequestContext, expect, request } from "@playwright/test";
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
        const response = await context.post(`${this.baseUrl}/integration/admin/token`, { data: { "username": process.env.MAGENTO_ADMIN_USER, "password": process.env.MAGENTO_ADMIN_PASSWORD } });

        expect(response.status(), "Unable to get Admin API token from Magento").toBe(200);

        this.token = await response.json();

        context.dispose();
    }

    async getOrder(incrementId: string): Promise<MagentoOrder> {
        // we send the Increment ID to Hokodo as that is the displayed Order ID in Magento
        // but behind the scenes the actual Order ID is stored as entity_id and it's not always the same as the Increment ID
        // the below query gets the Order by Increment ID
        const queryString = `searchCriteria[filter_groups][0][filters][0][field]=increment_id&searchCriteria[filter_groups][0][filters][0][value]=${incrementId}&searchCriteria[filter_groups][0][filters][0][condition_type]=eq`
        const response = await this.context.get(`${this.baseUrl}/orders?${queryString}`);
        
        expect(response.status(), "Unable to get the Order from the Magento API").toBe(200);

        return (await response.json()).items[0];
    }

    async dispose() {
        this.context.dispose();
    }
}
