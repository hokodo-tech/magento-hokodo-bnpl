import { MagentoComment } from "./MagentoComment";

export type MagentoOrder = {
    entity_id: number;
    shipping_amount: number;
    grand_total: number;
    base_currency_code: string;
    base_total_invoiced?: number;
    tax_amount: number;
    items: Array<MagentoBasketItem>;
    status_histories: Array<MagentoComment>;
    extension_attributes: {
        payment_additional_info: Array<{
            key: string,
            value: string
        }>
    }
}

export type MagentoBasketItem = {
    tax_amount: any;
    price: any;
    qty: any;
    name: any;
    item_id: number;
}

export enum MagentoOrderCaptureStatus {
    NotInvoiced,
    Invoiced,
    Captured,
}

export type MagentoBasketDetails = {
    totals: MagentoOrder;
}