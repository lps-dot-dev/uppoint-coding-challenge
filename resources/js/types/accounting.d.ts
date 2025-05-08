export type TransactionStatus = 'available' | 'failed' | 'pending';
export type TransactionType = 'deposit' | 'transfer' | 'withdrawl';

export interface DepositProcessed {
    id: number,
    status: TransactionStatus
};

export interface DepositCreated {
    id: number,
    amount: number,
    status: TransactionStatus,
    type: TransactionType
}

export interface Transaction {
    id: number,
    amount: number,
    status: TransactionStatus,
    type: TransactionType,
    created_at: string,
    updated_at: string
};
