<template>
    <div class="min-h-full ">
        <header class="relative bg-gray-800 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10 flex justify-center">
            <div class="container flex items-center justify-between">
                <div class="px-4 py-6 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold tracking-tight text-white">Dashboard</h1>
                </div>
                <div class="text-white">
                    <nav>
                        <ul class="flex space-x-2 items-center">
                            <template v-if="!loggedIn">
                                <li class="cursor-pointer" @click="showLoginModal = true">Login</li>
                            </template>
                            <template v-else>
                                <li>Add Order</li>
                                <li @click="logout" >{{ username }} (logout)</li>
                            </template>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>
        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <suspense v-if="loggedIn">
                    <WalletStats />
                </suspense>
                <suspense>
                    <OrderBookBySymbol />
                </suspense>
            </div>
        </main>
    </div>
    <Login
        v-if="showLoginModal"
        @logged-in="() => {
                loggedIn = true;
                showLoginModal = false
                refreshUserName()
            }" />
</template>

<script setup>
import { ref } from 'vue';
import Login from './components/Login.vue';
import OrderBookBySymbol from './components/OrderBookBySymbol.vue';
import WalletStats from './components/WalletStats.vue';

const storedUser = localStorage.getItem('user');

const loggedIn = ref(!! storedUser?.user?.name);
const username = ref('');

if (storedUser) {
    const user = JSON.parse(storedUser);
    loggedIn.value = true;
    username.value = user.name;
}

const showLoginModal = ref(false);

const refreshUserName = () => {
    username.value = JSON.parse(localStorage.getItem('user')).name;
}

const logout = () => {
    localStorage.removeItem('user');
    localStorage.removeItem('accessToken');
}

</script>
