<template>
    <div class="mt-8">
        <h1 class="text-2xl text-gray-50">Latest orders</h1>
    </div>
    <div class="my-4">
        <Listbox v-model="currentSymbol" @update:modelValue="fetchOrders">
            <div class="relative mt-1">
                <ListboxButton
                    class="relative w-full cursor-default rounded-lg bg-white py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-orange-300 sm:text-sm"
                >
                    <span class="block truncate">{{ currentSymbol }}</span>
                    <span
                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                    >
            <ChevronUpDownIcon
                class="h-5 w-5 text-gray-400"
                aria-hidden="true"
            />
          </span>
                </ListboxButton>

                <transition
                    leave-active-class="transition duration-100 ease-in"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <ListboxOptions
                        class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm"
                    >
                        <ListboxOption
                            v-slot="{ active, selected }"
                            v-for="s in symbols"
                            :key="s.symbol"
                            :value="s.symbol"
                            as="template"
                        >
                            <li
                                :class="[
                  active ? 'bg-amber-100 text-amber-900' : 'text-gray-900',
                  'relative cursor-default select-none py-2 pl-10 pr-4',
                ]"
                            >
                <span
                    :class="[
                    selected ? 'font-medium' : 'font-normal',
                    'block truncate',
                  ]"
                >{{ s.name }}</span
                >
                                <span
                                    v-if="selected"
                                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600"
                                >
                  <CheckIcon class="h-5 w-5" aria-hidden="true" />
                </span>
                            </li>
                        </ListboxOption>
                    </ListboxOptions>
                </transition>
            </div>
        </Listbox>
    </div>
    <div class="p-4 rounded text-gray-50">
        <div>
            <h3>Buy Orders</h3>
            <ul role="list" class="divide-y divide-white/5">
                <li v-for="o in buyOrders" :key="o.id" class="flex justify-between gap-x-6 py-5">
                    <div class="flex min-w-0 gap-x-4">
                        <div class="min-w-0 flex-auto">
                            <p class="text-sm/6 font-semibold text-white">#{{ o.id }}</p>
                        </div>
                    </div>
                    <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                        <p class="text-sm/6 text-white">Amount: {{ o.amount }}</p>
                        <p class="text-sm/6 text-white">Price: {{ o.price }}$</p>
                    </div>
                </li>
            </ul>
        </div>
        <hr class="my-4 border-gray-300" />
        <div>
            <h3>Sell Orders</h3>
            <ul role="list" class="divide-y divide-white/5">
                <li v-for="o in sellOrders" :key="o.id" class="flex justify-between gap-x-6 py-5">
                    <div class="flex min-w-0 gap-x-4">
                        <div class="min-w-0 flex-auto">
                            <p class="text-sm/6 font-semibold text-white">#{{ o.id }}</p>
                        </div>
                    </div>
                    <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                        <p class="text-sm/6 text-white">Amount: {{ o.amount }}</p>
                        <p class="text-sm/6 text-white">Price {{ o.price }}$</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { getJson } from '../composable/useFetch.js';
import {
    Listbox,
    ListboxLabel,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from '@headlessui/vue'
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'

const currentSymbol = ref('BTC');

const buyOrders = ref([])
const sellOrders = ref([])

const fetchOrders = async () => {
    const response = await getJson(`orders?symbol=${currentSymbol.value}`)
    const data = await response.json()
    buyOrders.value = data.buy
    sellOrders.value = data.sell
}

const symbols = [
    { name: 'Bitcoin', symbol: 'BTC' },
    { name: 'Ethereum', symbol: 'ETH' },
]

await fetchOrders()
</script>
