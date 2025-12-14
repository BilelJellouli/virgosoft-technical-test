<template>
    <Modal>
        <template #title>
            Login
        </template>
        <template #content>
            <form @submit.prevent="login" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-white">Email</label>
                    <input id="email" v-model="email" type="email" required
                           class="block w-full rounded-md bg-gray-700 border border-gray-600 text-white placeholder-gray-400
               px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
               shadow-sm sm:text-sm" />
                    <span v-if="loginError" class="text-red-500 my-2">The provided credentials are incorrect.</span>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-white">Password</label>
                    <input id="password" v-model="password" type="password" required
                           class="block w-full rounded-md bg-gray-700 border border-gray-600 text-white placeholder-gray-400
               px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
               shadow-sm sm:text-sm" />
                </div>
            </form>
        </template>
        <template #buttons>
            <button type="button"
                    class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2
                     text-sm font-semibold text-white hover:bg-green-500 sm:ml-3 sm:w-auto"
                    @click="login">
                Login
            </button>
            <button type="button"
                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white/10 px-3 py-2
                     text-sm font-semibold text-white inset-ring inset-ring-white/5 hover:bg-white/20 sm:mt-0 sm:w-auto"
                    @click="closeModal">
                Cancel
            </button>
        </template>
    </Modal>
</template>

<script setup>
import { ref } from 'vue'
import Modal from './Modal.vue'
import { postJson } from '../composable/useFetch.js';

const emit = defineEmits(['loggedIn', 'cancelled'])

const email = ref('')
const password = ref('')
const loginError = ref(false)
const loading = ref(false)

const login = async () => {
    loading.value = true
    loginError.value = false

    const response = (await postJson('login', {
        'email': email.value,
        'password': password.value
    }))

    loading.value = false

    if (! response.ok) {
        loginError.value = true
        return
    }

    const data = await response.json();

    localStorage.setItem('accessToken', data.accessToken)
    localStorage.setItem('user', JSON.stringify(data.user))

    emit('loggedIn')
}

function closeModal() {
    show.value = false
}
</script>
