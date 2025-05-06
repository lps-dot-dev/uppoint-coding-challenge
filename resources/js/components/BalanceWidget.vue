<script setup lang="ts">
import { BackendHttpClientSymbol } from '@/plugins/axios';
import { AxiosInstance } from 'axios';
import { computed, inject, onMounted, ref } from 'vue';
import DepositForm from './DepositForm.vue';

const backendHttpClient = inject<AxiosInstance>(BackendHttpClientSymbol);
const isLoading = ref(false);
const walletBalance = ref(0);

const formattedWalletBalance = computed(() => {
    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    });
    return formatter.format(walletBalance.value);
});

const fetchWalletBalance = () => {
    isLoading.value = true;
    backendHttpClient?.get('/api/balance')
        .then(response => {
            const { wallet_balance } = response.data;
            walletBalance.value = Number.parseFloat(wallet_balance);
        })
        .catch(error => {
            console.error(error);
        })
        .finally(() => {
            isLoading.value = false;
        });
};

onMounted(() => {
    fetchWalletBalance();
});
</script>

<template>
    <h1>Balance</h1>
    <p class="font-bold">{{ formattedWalletBalance }}</p>
    <hr class="my-2 h-px border-t-0 bg-transparent bg-gradient-to-r from-transparent via-neutral-500 to-transparent opacity-25 dark:via-neutral-400" />
    <DepositForm />
</template>
