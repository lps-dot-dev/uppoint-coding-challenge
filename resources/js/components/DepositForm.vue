<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

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

const form = useForm({
    amount: '0',
});

const closeModal = () => {
    form.clearErrors();
    form.reset();
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
            <form class="space-y-6">
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

                    <Button variant="destructive" :disabled="amountValidationMessage !== undefined">
                        <button type="submit">Submit</button>
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
