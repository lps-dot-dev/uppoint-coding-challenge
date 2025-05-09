<script setup lang="ts">
import { AxiosInstance } from 'axios';
import { BackendHttpClientSymbol } from '@/plugins/axios';
import { inject, reactive, ref, onMounted, computed, onUnmounted } from 'vue';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import { useAccountingStore } from '@/stores/accounting';
import Echo from 'laravel-echo';
import { EchoSymbol } from '@/plugins/echo';
import { usePage } from '@inertiajs/vue3';
import { SharedData } from '@/types';

const accountingStore = useAccountingStore();
const backendHttpClient = inject<AxiosInstance>(BackendHttpClientSymbol);
const echo = inject<Echo<'reverb'>>(EchoSymbol);
const page = usePage<SharedData>();
const userId = page.props.auth.user.id;

const columns = ref([
    { field: 'uuid', title: 'UUID', isUnique: true },
    { field: 'amount', title: 'Amount' },
    { field: 'status', title: 'Status' },
    { field: 'type', title: 'Type' },
    { field: 'created_at', title: 'Created At' },
    { field: 'updated_at', title: 'Updated At' },
]);
const params = reactive({ currentPage: 1, pageSize: 10 });
const rows = computed(() => accountingStore.transactionsList);

const isLoading = ref(false);
const totalRows = ref(0);

onMounted(() => {
    getTransactions(params.currentPage);
    const accountingChannel = echo?.private(`Accounting.${userId}`);
    accountingChannel?.listen('.deposit.created', () => {
        totalRows.value++;
        getTransactions(Math.ceil(totalRows.value / params.pageSize));
    });
});

onUnmounted(() =>{
    echo?.leaveChannel(`Accounting.${userId}`);
});

const getTransactions = async (pageNumber: number) => {
    isLoading.value = true;
    backendHttpClient?.get('/api/transaction', { params: { 'page': pageNumber } })
        .then(response => {
            const { data, total } = response.data;
            accountingStore.setTransactions(data);
            totalRows.value = total;
        })
        .catch(error => {
            console.error(error);
        })
        .finally(() => {
            isLoading.value = false;
        });
};
const handlePageChange = (data: any) => {
    params.currentPage = data.current_page;
    params.pageSize = data.pagesize;

    getTransactions(data.current_page);
};
</script>

<template>
    <vue3-datatable
        :rows="rows"
        :columns="columns"
        :loading="isLoading"
        :totalRows="totalRows"
        :isServerMode="true"
        :pageSize="params.pageSize"
        firstArrow="First"
        lastArrow="Last"
        previousArrow="Prev"
        nextArrow="Next"
        skin="bh-table-hover"
        class="mx-4"
        :showNumbersCount="3"
        @change="handlePageChange"
    >
    </vue3-datatable>
</template>
