<script setup lang="ts">
    import { AxiosInstance } from 'axios';
    import { BackendHttpClientSymbol } from '@/plugins/axios';
    import { inject, reactive, ref, onMounted } from 'vue';
    import Vue3Datatable from '@bhplugin/vue3-datatable';

    const backendHttpClient = inject<AxiosInstance>(BackendHttpClientSymbol);
    const isLoading = ref(false);
    const totalRows = ref(0);

    const columns = ref([
        { field: 'id', title: 'ID', isUnique: true, type: 'number' },
        { field: 'amount', title: 'Amount' },
        { field: 'status', title: 'Status' },
        { field: 'type', title: 'Type' },
        { field: 'created_at', title: 'Created At' },
        { field: 'updated_at', title: 'Updated At' },
    ]);
    const params = reactive({ currentPage: 1, pageSize: 10 });
    const rows = ref([]);

    onMounted(() => {
        getTransactions(params.currentPage);
    });

    const getTransactions = async (pageNumber: number) => {
        isLoading.value = true;
        backendHttpClient?.get('/api/transaction', { params: { 'page': pageNumber } })
            .then(response => {
                const { data, total } = response.data;
                rows.value = data;
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
        @page-change="handlePageChange"
    >
    </vue3-datatable>
</template>
