<script setup lang="ts">
import type { InertiaForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import type {
    AdminStatusOption,
    OrderUpdateFormData,
} from '@/types/admin';

type Props = {
    form: InertiaForm<OrderUpdateFormData>;
    statusOptions: AdminStatusOption[];
    paymentStatusOptions: AdminStatusOption[];
};

defineProps<Props>();

const selectClass =
    'border-input bg-background ring-offset-background focus-visible:ring-ring flex h-9 w-full rounded-md border px-3 py-1 text-sm shadow-xs transition-[color,box-shadow] outline-none focus-visible:ring-[3px] disabled:cursor-not-allowed disabled:opacity-50';

const textareaClass =
    'border-input bg-background ring-offset-background placeholder:text-muted-foreground focus-visible:ring-ring flex min-h-24 w-full rounded-md border px-3 py-2 text-sm shadow-xs transition-[color,box-shadow] outline-none focus-visible:ring-[3px] disabled:cursor-not-allowed disabled:opacity-50';
</script>

<template>
    <div class="grid gap-6">
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="grid gap-2">
                <Label for="status">Order status</Label>
                <select
                    id="status"
                    v-model="form.status"
                    :class="selectClass"
                    required
                >
                    <option
                        v-for="option in statusOptions"
                        :key="option.value"
                        :value="option.value"
                    >
                        {{ option.label }}
                    </option>
                </select>
                <InputError :message="form.errors.status" />
            </div>

            <div class="grid gap-2">
                <Label for="payment_status">Payment status</Label>
                <select
                    id="payment_status"
                    v-model="form.payment_status"
                    :class="selectClass"
                    required
                >
                    <option
                        v-for="option in paymentStatusOptions"
                        :key="option.value"
                        :value="option.value"
                    >
                        {{ option.label }}
                    </option>
                </select>
                <InputError :message="form.errors.payment_status" />
            </div>
        </div>

        <div class="grid gap-2">
            <Label for="note">Note (optional)</Label>
            <textarea
                id="note"
                v-model="form.note"
                :class="textareaClass"
                placeholder="Add a note for the status history"
            />
            <InputError :message="form.errors.note" />
        </div>

        <div class="flex justify-end">
            <Button type="submit" :disabled="form.processing">
                Update order
            </Button>
        </div>
    </div>
</template>
