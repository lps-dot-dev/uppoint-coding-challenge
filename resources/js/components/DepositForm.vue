<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, inject, ref } from 'vue';
import { AxiosInstance } from 'axios';
import { BackendHttpClientSymbol } from '@/plugins/axios';
import { useToast } from 'primevue/usetoast';

// Components
import InputError from '@/components/InputError.vue';
import Select from './ui/select/Select.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const backendHttpClient = inject<AxiosInstance>(BackendHttpClientSymbol);
const form = useForm({
    amount: '0',
    source: 'WELLS FARGO NA (1234)'
});
const isSubmitting = ref(false);
const toast = useToast();

const closeModal = () => {
    form.clearErrors();
    form.reset();
};

const submitDeposit = () => {
    isSubmitting.value = true;
    backendHttpClient?.post('/api/deposit', { amount: form.amount, source: form.source })
        .then(() => {
            toast.add({ severity: 'info', summary: `New Deposit`, detail: 'Submitting...', life: 3000 });
            closeModal();
        })
        .catch(error => {
            toast.add({ severity: 'error', summary: `New Deposit`, detail: 'Failed to submit! Please try again later!', life: 3000 });
            console.error(error);
        })
        .finally(() => {
            isSubmitting.value = false;
        });
};

const amountValidationMessage = computed(() => {
    if (isNaN(form.amount)) {
        return "The amount must be numeric! Please try again!"
    }

    if (Number.parseFloat(form.amount) <= 1.0) {
        return "The amount must be greater than $1.00!";
    }

    return undefined;
});
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button variant="destructive">New Deposit</Button>
        </DialogTrigger>
        <DialogContent>
            <form class="space-y-6" @submit.prevent>
                <DialogHeader class="space-y-3">
                    <DialogTitle>Initialize New Deposit</DialogTitle>
                    <DialogDescription>
                        Please review this carefully, as you cannot undo this action once submitted.
                    </DialogDescription>
                </DialogHeader>

                <div class="grid gap-2">
                    <Label for="wallet-source" class="sr-only">Wallet Source</Label>
                    <Select id="wallet-source" />
                </div>

                <div class="grid gap-2">
                    <Label for="amount" class="sr-only">Amount</Label>
                    <Input id="amount" type="amount" name="amount" ref="amountInput" v-model="form.amount" placeholder="Amount" />
                    <InputError :message="amountValidationMessage" />
                </div>

                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button variant="secondary" @click="closeModal"> Cancel </Button>
                    </DialogClose>

                    <DialogClose as-child>
                        <Button variant="destructive" :disabled="amountValidationMessage !== undefined" @click="submitDeposit">
                            Submit
                        </Button>
                    </DialogClose>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
