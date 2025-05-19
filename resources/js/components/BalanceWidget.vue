<script setup lang="ts">
import { BackendHttpClientSymbol } from '@/plugins/axios';
import { AxiosInstance } from 'axios';
import { computed, inject, onMounted, onUnmounted, ref } from 'vue';
import DepositForm from './DepositForm.vue';
import Echo from 'laravel-echo';
import { EchoSymbol } from '@/plugins/echo';
import { usePage } from '@inertiajs/vue3';
import { SharedData } from '@/types';

const backendHttpClient = inject<AxiosInstance>(BackendHttpClientSymbol);
const echo = inject<Echo<'reverb'>>(EchoSymbol);
const page = usePage<SharedData>();
const userUuid = page.props.auth.user.uuid;

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

    const accountingChannel = echo?.private(`Accounting.${userUuid}`);
    accountingChannel?.listen('.balance.updated', () => {
        fetchWalletBalance();
    });
});

onUnmounted(() => {
    echo?.leaveChannel(`Accounting.${userUuid}`);
})
</script>

<template>
    <h1>Balance</h1>
    <p class="font-bold">{{ formattedWalletBalance }}</p>
    <hr class="my-2 h-px border-t-0 bg-transparent bg-gradient-to-r from-transparent via-neutral-500 to-transparent opacity-25 dark:via-neutral-400" />
    <DepositForm />
</template>
