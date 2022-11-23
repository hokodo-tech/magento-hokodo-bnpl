import { Address } from "./Address";
import { Buyer } from "./Buyer";
import { Product } from "./Product";

export type OrderData = {
    buyer: Buyer;
    shippingAddress: Address;
    billingAddress: Address;
    products: Product[];
}