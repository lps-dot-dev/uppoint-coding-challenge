<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type SharedData, type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import TransactionsTable from '@/components/TransactionsTable.vue';
import BalanceWidget from '@/components/BalanceWidget.vue';
import Toast from '@/components/ui/toast/Toast.vue';
import { computed, inject, onMounted, onUnmounted, ref } from 'vue';
import Echo from 'laravel-echo';
import { EchoSymbol } from '@/plugins/echo';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
const displayToast = ref(false);
const toastDescription = ref('');
const toastTitle = ref('Accounting');

const echo = inject<Echo<'reverb'>>(EchoSymbol);
const page = usePage<SharedData>();
const user = computed(() => page.props.auth.user);

onMounted(() => {
    const accountingChannel = echo?.private(`Accounting.${user.value.id}`);
    accountingChannel?.listen('.deposit.created', () => {
        toastDescription.value = 'Deposit has been initiated!';
        displayToast.value = true;
    });

    accountingChannel?.listen('.deposit.processed', () => {
        toastDescription.value = 'Deposit has been processed!';
        displayToast.value = true;
    });
});

onUnmounted(() => {
    echo?.leaveChannel(`Accounting.${user.value.id}`);
});

</script>

<template>
    <Head title="Dashboard" />
    <Toast :description="toastDescription" :display="displayToast" :title="toastTitle" />
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
