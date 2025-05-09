export type TransactionStatus = 'available' | 'failed' | 'pending';
export type TransactionType = 'deposit' | 'transfer' | 'withdrawl';

export interface DepositProcessed {
    uuid: string,
    status: TransactionStatus
};

export interface DepositCreated {
    uuid: string
}

export interface Transaction {
    uuid: string,
    amount: number,
    status: TransactionStatus,
    type: TransactionType,
    created_at: string,
    updated_at: string
};
