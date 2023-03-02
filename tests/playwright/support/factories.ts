import { Address } from "./types/Address";
import { Buyer, BuyerStatus, CompanyType, CreditStatus, DeferredPaymentStatus } from "./types/Buyer";

export function generateBuyerData(type: CompanyType = CompanyType.REGISTERED_COMPANY, buyerStatus: BuyerStatus = { fraudStatus: DeferredPaymentStatus.ACCEPTED, creditStatus: CreditStatus.OFFERED }, 
    {
        firstName = "Derek",
        lastName = "Trotter",
        email = `test+${Date.now().toString().slice(-5)}_paymentplan_${buyerStatus.creditStatus.replace(/-/gi, "_")}-dp_fraud_${buyerStatus.fraudStatus.replace(/-/gi, "_")}@hokodo.co`,
        password = "Password1!",
        companyName = "Hokodo Ltd",
        companyType = type,
        companyCountry = "GB",
    }: Buyer = {}): Buyer {
    return {
        firstName,
        lastName,
        email,
        password,
        companyName,
        companyType,
        companyCountry,
        ownerAddress: type === CompanyType.SOLE_TRADER ? generateAddress() : null,
        companyAddress: type === CompanyType.SOLE_TRADER ? generateAddress() : null,
        dateOfBirth: type === CompanyType.SOLE_TRADER ? "31101976" : null,
    }
};


export function generateAddress(): Address {
    return {
        name: "Derek Trotter",
        company_name: "Hokodo Ltd",
        address_line1: "35 Kingsland Rd",
        address_line2: "",
        city: "London",
        region: "",
        postcode: "E22 8AA",
        country: "GB",
        phone: "01892 555 3123",
        email: ""
    }
}