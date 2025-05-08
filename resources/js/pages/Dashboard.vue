<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type SharedData, type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import TransactionsTable from '@/components/TransactionsTable.vue';
import BalanceWidget from '@/components/BalanceWidget.vue';
import Toast from 'primevue/toast';

import { inject, onMounted, onUnmounted } from 'vue';
import Echo from 'laravel-echo';
import { EchoSymbol } from '@/plugins/echo';
import { useToast } from "primevue/usetoast";
import { DepositProcessed, Transaction } from '@/types/accounting';
import { useAccountingStore } from '@/stores/accounting';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const accountingStore = useAccountingStore();
const echo = inject<Echo<'reverb'>>(EchoSymbol);
const toast = useToast();
const page = usePage<SharedData>();
const userId = page.props.auth.user.id;

onMounted(() => {
    const accountingChannel = echo?.private(`Accounting.${userId}`);
    accountingChannel?.listen('.deposit.created', (deposit: Transaction) => {
        toast.add({ severity: 'info', summary: `Deposit ${deposit.id}`, detail: 'Submitted, pending processing', life: 3000 });
    });

    accountingChannel?.listen('.deposit.processed', (depositProcessed: DepositProcessed) => {
        if (depositProcessed.status === 'available') {
            toast.add({ severity: 'success', summary: `Deposit ${depositProcessed.id}`, detail: 'Processed successfully!', life: 3000 });
        } else if (depositProcessed.status === 'failed') {
            toast.add({ severity: 'error', summary: `Deposit ${depositProcessed.id}`, detail: 'Processing failed!', life: 3000 });
        }

        accountingStore.updateTransactionStatus(depositProcessed.id, depositProcessed.status);
    });
});

onUnmounted(() => {
    echo?.leaveChannel(`Accounting.${userId}`);
});

</script>

<template>
    <Head title="Dashboard" />
    <Toast />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4">
                    <BalanceWidget />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:bg-zinc-100 dark:border-sidebar-border md:min-h-min">
                <TransactionsTable />
            </div>
        </div>
    </AppLayout>
</template>
