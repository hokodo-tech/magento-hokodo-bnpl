export type DeferredPayment = {
    id: string;
    status: string;
    authorisation: number;
    protected_captures: number;
    unprotected_captures: number;
    refunds: number;
    voided_authorisation: number;
    expired_authorisation: number;
}