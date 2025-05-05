<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import InactivityWarning from '@/components/InactivityWarning.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <InactivityWarning />
        <slot />
    </AppLayout>
</template>
