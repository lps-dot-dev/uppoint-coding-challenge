<script setup lang="ts">
import { ToastAction, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'reka-ui';

interface ToastProps {
    description: string,
    display: boolean,
    duration?: number,
    title: string
};

const props = defineProps<ToastProps>();
</script>

<template>
  <ToastProvider>
    <ToastRoot
      v-model:open="props.display"
      :duration="props.duration"
      class="bg-white rounded-lg shadow-sm border p-[15px] grid [grid-template-areas:_'title_action'_'description_action'] grid-cols-[auto_max-content] gap-x-[15px] items-center data-[state=open]:animate-slideIn data-[state=closed]:animate-hide data-[swipe=move]:translate-x-[var(--reka-toast-swipe-move-x)] data-[swipe=cancel]:translate-x-0 data-[swipe=cancel]:transition-[transform_200ms_ease-out] data-[swipe=end]:animate-swipeOut"
    >
      <ToastTitle class="[grid-area:_title] mb-[5px] font-medium text-slate12 text-sm">
        {{ props.title }}
      </ToastTitle>
      <ToastDescription as-child>
        {{ props.description }}
      </ToastDescription>
      <ToastAction
        class="[grid-area:_action]"
        as-child
        alt-text="Goto schedule to undo"
      >
        <button class="inline-flex items-center justify-center rounded-md font-medium text-xs px-[10px] leading-[25px] h-[25px] bg-green2 text-green11 shadow-[inset_0_0_0_1px] shadow-green7 hover:shadow-[inset_0_0_0_1px] hover:shadow-green8 focus:shadow-[0_0_0_2px] focus:shadow-green8">
          Undo
        </button>
      </ToastAction>
    </ToastRoot>
    <ToastViewport class="[--viewport-padding:_25px] fixed bottom-0 right-0 flex flex-col p-[var(--viewport-padding)] gap-[10px] w-[390px] max-w-[100vw] m-0 list-none z-[2147483647] outline-none" />
  </ToastProvider>
</template>
