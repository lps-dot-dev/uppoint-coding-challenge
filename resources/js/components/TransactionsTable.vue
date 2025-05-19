<script setup lang="ts">
import { AxiosInstance } from 'axios';
import { BackendHttpClientSymbol } from '@/plugins/axios';
import { inject, reactive, ref, onMounted, computed, onUnmounted } from 'vue';
import { useAccountingStore } from '@/stores/accounting';
import { EchoSymbol } from '@/plugins/echo';
import { usePage } from '@inertiajs/vue3';
import { SharedData } from '@/types';

import moment from 'moment-timezone';
import Echo from 'laravel-echo';
import Tag from 'primevue/tag';
import Vue3Datatable from '@bhplugin/vue3-datatable';

const accountingStore = useAccountingStore();
const backendHttpClient = inject<AxiosInstance>(BackendHttpClientSymbol);
const echo = inject<Echo<'reverb'>>(EchoSymbol);
const page = usePage<SharedData>();
const userUuid = page.props.auth.user.uuid;
const usdFormat = new Intl.NumberFormat('en-US', {
  style: 'currency',
  currency: 'USD'
});

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
    const accountingChannel = echo?.private(`Accounting.${userUuid}`);
    accountingChannel?.listen('.deposit.created', () => {
        totalRows.value++;
        getTransactions(Math.ceil(totalRows.value / params.pageSize));
    });
});

onUnmounted(() =>{
    echo?.leaveChannel(`Accounting.${userUuid}`);
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

const formatTimestamp = (timestamp: string): string => {
    const utcMoment = moment.tz(timestamp, 'UTC');
    const currentMoment = utcMoment.clone().tz(moment.tz.guess());
    return currentMoment.format('Y-MM-DD h:mm:ssa');
};

const getStatusLabel = (status: string) => {
    switch (status) {
        case 'available':
            return 'success';
        case 'failed':
            return 'danger';
        default:
        case 'pending':
            return 'info';
    }
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
        <template #amount="data">
            {{  usdFormat.format(data?.value?.amount) }}
        </template>
        <template #status="data">
            <Tag
                :severity="getStatusLabel(data?.value?.status)"
                :value="data?.value?.status"
            ></Tag>
        </template>
        <template #created_at="data">
            {{ formatTimestamp(data?.value?.created_at) }}
        </template>
        <template #updated_at="data">
            {{ formatTimestamp(data?.value?.updated_at) }}
        </template>
    </vue3-datatable>
</template>
