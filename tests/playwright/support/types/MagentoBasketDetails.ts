export type MagentoBasketDetails = {
    totals: MagentoBasketTotals;
}

export type MagentoBasketTotals = {
    shipping_amount: number;
    grand_total: number;
    base_currency_code: string;
    tax_amount: number;
    items: Array<MagentoBasketItem>;
}

export type MagentoBasketItem = {
    tax_amount: any;
    price: any;
    qty: any;
    name: any;
    item_id: number;
}