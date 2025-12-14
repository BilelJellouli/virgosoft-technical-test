<template>
    <div class="w-full px-4 py-6 flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
        <!-- USD Box -->
        <div class="flex-1 bg-gray-800 rounded-lg p-6 text-white shadow-md">
            <h3 class="text-lg font-semibold">USD</h3>
            <p class="mt-2 text-2xl font-bold">{{ balances.usd }}</p>
        </div>

        <!-- BTC Box -->
        <div class="flex-1 bg-gray-800 rounded-lg p-6 text-white shadow-md">
            <h3 class="text-lg font-semibold">BTC</h3>
            <p class="mt-2 text-2xl font-bold">{{ balances.assets.btc || 0 }}</p>
        </div>

        <!-- ETH Box -->
        <div class="flex-1 bg-gray-800 rounded-lg p-6 text-white shadow-md">
            <h3 class="text-lg font-semibold">ETH</h3>
            <p class="mt-2 text-2xl font-bold">{{ balances.assets.eth || 0 }}</p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getJson } from '../composable/useFetch.js';

const balances = ref({ usd: 0, assets: {} });

const fetchBalances = async () => {
    const response = await getJson('profile');
    const data = await response.json();

    balances.value.usd = data.profile.balance;
    balances.value.assets = {
        btc: data.profile.assets.find(({symbol}) => symbol === 'BTC').amount,
        eth: data.profile.assets.find(({symbol}) => symbol === 'ETH').amount,
    };
};

onMounted(fetchBalances);
</script>
