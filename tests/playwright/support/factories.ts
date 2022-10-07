import { Address } from "./types/Address";
import { Buyer, CompanyType } from "./types/Buyer";

export function generateBuyerData({
    firstName = "Derek",
    lastName = "Trotter",
    email = `test+${Date.now().toString().slice(-5)}_paymentplan_offered_dp_fraud_accepted@hokodo.co`,
    password = "Password1!",
    companyName = "Hokodo Ltd",
    companyType = CompanyType.REGISTERED_COMPANY,
    companyCountry = "GB"
}: Buyer = {}): Buyer {
    return {        
        firstName,
        lastName,
        email,
        password,
        companyName,
        companyType,
        companyCountry,
    }
};

export function generateAddress({
    companyName = "Hokodo Ltd",
    lineOne = "35 Kingsland Rd",
    city = "London",
    state = "London",
    postCode = "E22 8AA",
    countryCode = "GB",
    phoneNumber = "01892 555 3123",
}: Address = { }): Address {
    return {
        companyName,
        lineOne,
        city,
        state,
        postCode,
        countryCode,
        phoneNumber,
    }
}