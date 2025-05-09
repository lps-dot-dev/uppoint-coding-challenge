<script setup lang="ts">
import { Icon } from '@iconify/vue'
import {
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectItemIndicator,
  SelectItemText,
  SelectLabel,
  SelectPortal,
  SelectRoot,
  SelectScrollDownButton,
  SelectScrollUpButton,
  SelectSeparator,
  SelectTrigger,
  SelectValue,
  SelectViewport,
} from 'reka-ui'
import { ref } from 'vue'

const balanceSource = ref('CHASE DEBIT (3214)');

const bankAccounts = ['WELLS FARGO NA (1234)', 'CHASE BANK ACCOUNT NA (7890)'];
const debitCards = ['CHASE DEBIT (3214)'];
</script>

<template>
  <SelectRoot v-model="balanceSource">
    <SelectTrigger
      class="inline-flex min-w-[160px] items-center justify-between rounded-lg px-[15px] text-xs leading-none h-[35px] gap-[5px] bg-white dark:bg-black text-grass11 hover:bg-stone-50 border shadow-sm focus:shadow-[0_0_0_2px] focus:shadow-black data-[placeholder]:text-green9 outline-none"
      aria-label="Customise options"
    >
      <SelectValue placeholder="Select a source..." />
      <Icon
        icon="radix-icons:chevron-down"
        class="h-3.5 w-3.5"
      />
    </SelectTrigger>

    <SelectPortal>
      <SelectContent
        class="min-w-[160px] bg-white dark:bg-black rounded-lg border shadow-sm will-change-[opacity,transform] data-[side=top]:animate-slideDownAndFade data-[side=right]:animate-slideLeftAndFade data-[side=bottom]:animate-slideUpAndFade data-[side=left]:animate-slideRightAndFade z-[100]"
        :side-offset="5"
      >
        <SelectScrollUpButton class="flex items-center justify-center h-[25px] bg-white dark:bg-black text-violet11 cursor-default">
          <Icon icon="radix-icons:chevron-up" />
        </SelectScrollUpButton>

        <SelectViewport class="p-[5px]">
          <SelectLabel class="px-[25px] text-xs leading-[25px] text-zinc-500">
            Bank Accounts
          </SelectLabel>
          <SelectGroup>
            <SelectItem
              v-for="(option, index) in bankAccounts"
              :key="index"
              class="text-xs leading-none rounded-[3px] flex items-center h-[25px] pr-[35px] pl-[25px] relative select-none data-[disabled]:text-zinc-500 data-[disabled]:pointer-events-none data-[highlighted]:outline-none data-[highlighted]:text-zinc-300 data-[highlighted]:text-zinc-400"
              :value="option"
            >
              <SelectItemIndicator class="absolute left-0 w-[25px] inline-flex items-center justify-center">
                <Icon icon="radix-icons:check" />
              </SelectItemIndicator>
              <SelectItemText>
                {{ option }}
              </SelectItemText>
            </SelectItem>
          </SelectGroup>
          <SelectSeparator class="h-[1px] bg-zinc-600 m-[5px]" />
          <SelectLabel class="px-[25px] text-xs leading-[25px] text-zinc-500">
            Debit Cards
          </SelectLabel>
          <SelectGroup>
            <SelectItem
              v-for="(option, index) in debitCards"
              :key="index"
              class="text-xs leading-none rounded-[3px] flex items-center h-[25px] pr-[35px] pl-[25px] relative select-none data-[disabled]:text-zinc-500 data-[disabled]:pointer-events-none data-[highlighted]:outline-none data-[highlighted]:text-zinc-300 data-[highlighted]:text-zinc-400"
              :value="option"
              :disabled="option === 'Courgette'"
            >
              <SelectItemIndicator class="absolute left-0 w-[25px] inline-flex items-center justify-center">
                <Icon icon="radix-icons:check" />
              </SelectItemIndicator>
              <SelectItemText>
                {{ option }}
              </SelectItemText>
            </SelectItem>
          </SelectGroup>
        </SelectViewport>

        <SelectScrollDownButton class="flex items-center justify-center h-[25px] bg-white dark:bg-black text-violet11 cursor-default">
          <Icon icon="radix-icons:chevron-down" />
        </SelectScrollDownButton>
      </SelectContent>
    </SelectPortal>
  </SelectRoot>
</template>
