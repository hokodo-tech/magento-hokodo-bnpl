import { Address } from "./Address"

export type Buyer = {
    firstName?: string,
    lastName?: string,
    email?: string,
    password?: string,
    companyName?: string,
    companyType?: CompanyType,
    companyCountry?: string,
    dateOfBirth?: string | null,
    companyAddress?: Address | null,
    ownerAddress?: Address | null,
}

export type BuyerStatus = {
    creditStatus: CreditStatus,
    fraudStatus: FraudStatus,
}

export enum CompanyType {
    SOLE_TRADER = "sole-trader",
    REGISTERED_COMPANY = "registered-company",
}

export enum CreditStatus {
    OFFERED = "offered",
    DECLINED = "declined",
    PARTLY_OFFERED = "partly-offered"
}

export enum FraudStatus {
    ACCEPTED = "accepted",
    REJECTED = "rejected",
    CUSTOMER_ACTION_REQUIRED = "customer-action-required",
    PENDING_REVIEW = "pending-review"
}
