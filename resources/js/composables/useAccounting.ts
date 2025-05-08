import { Transaction, TransactionStatus } from "@/types/accounting";
import { computed, ref } from "vue";

export function useAccounting() {
    const transactions = ref<Map<number, Transaction>>(new Map());
    const transactionsList = computed(() => Array.from(transactions.value.values()));

    function addTransaction(transaction: Transaction): void {
        transactions.value.set(transaction.id, transaction);
    }

    function setTransactions(newTransactions: Transaction[]): void {
        transactions.value.clear();
        newTransactions.forEach((transaction: Transaction) => {
            addTransaction(transaction);
        });
    }

    function updateTransactionStatus(transactionId: number, status: TransactionStatus): void {
        let transaction = transactions.value.get(transactionId);
        if (transaction === undefined) {
            return;
        }

        transaction.status = status;
        addTransaction(transaction);
    }

    return { addTransaction, setTransactions, updateTransactionStatus, transactionsList };
};
