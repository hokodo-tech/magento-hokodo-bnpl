import { Address } from "./Address";
import { DeferredPayment } from "./DeferredPayment";

export type HokodoOrder = {
    unique_id: string;
    currency: string;
    total_amount: number;
    tax_amount: number;
    items: Array<HokodoOrderItem>;
    deferred_payment: DeferredPayment;
    customer: HokodoCustomer;
    id: string;
}

export type HokodoOrderItem = {
    type: string;
    item_id: string;
    description: string;
    quantity: string;
    unit_price: number;
    tax_amount: number;
    fulfilled_quantity: string;
    cancelled_quantity: string;
    returned_quantity: string;
}

export type HokodoCustomer = {
    delivery_address: Address;
    invoice_address: Address;
    organisation: string;
}