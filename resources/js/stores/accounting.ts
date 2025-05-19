import { Transaction, TransactionStatus } from "@/types/accounting";
import { defineStore } from "pinia";
import { computed, ref } from "vue";

export const useAccountingStore = defineStore('accounting', () => {
    const transactions = ref<Map<string, Transaction>>(new Map());
    const transactionsList = computed(() => Array.from(transactions.value.values()));

    function addTransaction(transaction: Transaction): void {
        transactions.value.set(transaction.uuid, transaction);
    }

    function setTransactions(newTransactions: Transaction[]): void {
        transactions.value.clear();
        newTransactions.forEach((transaction: Transaction) => {
            addTransaction(transaction);
        });
    }

    function updateTransactionStatus(transactionUuid: string, status: TransactionStatus): void {
        let transaction = transactions.value.get(transactionUuid);
        if (transaction === undefined) {
            return;
        }

        transaction.status = status;
        addTransaction(transaction);
    }

    return { addTransaction, setTransactions, updateTransactionStatus, transactionsList };
});
