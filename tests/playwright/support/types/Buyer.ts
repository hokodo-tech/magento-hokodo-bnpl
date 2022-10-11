export type Buyer = {
    firstName?: string,
    lastName?: string,
    email?: string,
    password?: string,
    companyName?: string,
    companyType?: CompanyType,
    companyCountry?: string
}

export enum CompanyType {
    SOLE_TRADER = "sole-trader",
    REGISTERED_COMPANY = "registered-company",
  }