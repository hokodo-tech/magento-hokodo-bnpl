import { Address } from "./Address";

export type Buyer = {
  firstName?: string;
  lastName?: string;
  email?: string;
  password?: string;
  companyName?: string;
  companyType?: CompanyType;
  companyCountry?: string;
  dateOfBirth?: string | null;
  companyAddress?: Address | null;
  ownerAddress?: Address | null;
};

export type BuyerStatus = {
  creditStatus: CreditStatus;
  fraudStatus: DeferredPaymentStatus;
};

export enum CompanyType {
  SOLE_TRADER = "Sole Trader",
  REGISTERED_COMPANY = "registered-company",
}

export enum CreditStatus {
  OFFERED = "offered",
  DECLINED = "declined",
  PARTLY_OFFERED = "partly-offered",
}

export enum DeferredPaymentStatus {
  ACCEPTED = "accepted",
  REJECTED = "rejected",
  CUSTOMER_ACTION_REQUIRED = "customer_action_required",
  PENDING_REVIEW = "pending_review",
  VOIDED = "voided",
  ACCEPTED_PENDING_PAYMENT = "accepted_pending_payment",
  CAPTURED = "captured",
}
