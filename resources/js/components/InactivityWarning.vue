<script setup lang="ts">
import { computed, inject, onMounted, onUnmounted, ref, watch } from 'vue';

// Components
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle
} from '@/components/ui/dialog';
import { AxiosInstance } from 'axios';
import { BackendHttpClientSymbol } from '@/plugins/axios';
import { Link, router } from '@inertiajs/vue3';

const backendHttpClient = inject<AxiosInstance>(BackendHttpClientSymbol);
const showInactivityDialog = ref<boolean>(false);
const inactivityCounter = ref(0);
var intervalId: number;

onMounted(() => {
    intervalId = setInterval(() => {
        inactivityCounter.value++
    }, 1000);
});

onUnmounted(() => {
    clearInterval(intervalId);
});

watch(inactivityCounter, (newInactivityCounter) => {
    if (newInactivityCounter >= 210 && showInactivityDialog.value === false) {
        showInactivityDialog.value = true;
    }

    if (newInactivityCounter > 295 && showInactivityDialog.value) {
        router.visit('/logout', { method: 'post' });
    }
});

const timeRemainingLabel = computed<string>(() => {
    const minutesIdle = Math.ceil(inactivityCounter.value / 60);
    const minutesRemaining = 5 - minutesIdle;
    return minutesRemaining > 1 ? `${minutesRemaining} minutes` : 'less than a minute';
});

const refreshToken = () => {
    backendHttpClient?.post('/api/refresh');
    showInactivityDialog.value = false;
    inactivityCounter.value = 0;
};
</script>

<template>
    <Dialog :open="showInactivityDialog">
        <DialogContent>
            <DialogHeader class="space-y-3">
                <DialogTitle>Are you still there?</DialogTitle>
                <DialogDescription>
                    You will be signed out in {{ timeRemainingLabel }} due to inactivity.
                </DialogDescription>
            </DialogHeader>

            <DialogFooter class="gap-2">
                <Link
                    href="/logout"
                    method="post"
                    as="button"
                    class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                >
                    Sign Out
                </Link>

                <Button variant="default" @click="refreshToken">
                    Still Here!
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
