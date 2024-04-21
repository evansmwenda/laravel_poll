<template>
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
          <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company" />
          <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Register for free</h2>
        </div>
    
        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
          <form class="space-y-6" @submit="register">
            <Alert v-if="errorMsg">
            {{ errorMsg }}
            <span
              @click="errorMsg = ''"
              class="w-8 h-8 flex items-center justify-center rounded-full transition-colors cursor-pointer hover:bg-[rgba(0,0,0,0.2)]"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </span>
          </Alert>

            <!-- <Alert v-if="Object.keys(errors).length" class="flex-col items-stretch text-sm">
              <div v-for="(field, i) of Object.keys(errors)" :key="i">
                <div v-for="(error, ind) of errors[field] || []" :key="ind">
                  * {{ error }}
                </div>
              </div>
            </Alert> -->

            <div>
              <div class="mt-2">
                <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Full Name</label>              
                <input id="name" name="name" type="text" autocomplete="name" required="" v-model="user.name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
              </div>
            </div>
  
            <div>
              <div class="mt-2">
                <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                <input id="email" name="email" type="email" autocomplete="email" required="" v-model="user.email" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
              </div>
            </div>
            <div>
              <div class="mt-2">
                <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                <input id="password" name="password" type="password" autocomplete="current-password" v-model="user.password" required="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
              </div>
            </div>
    
            <div>
              <div class="mt-2">
                <label for="password_confirmation" class="block text-sm font-medium leading-6 text-gray-900">Password Confirmation</label>
                <input id="password_confirmation" name="password_confirmation" type="password" v-model="user.password_confirmation" autocomplete="current-password_confirmation" required="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
              </div>
            </div>
  
            
    
            <div>
              <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign Up</button>
            </div>
          </form>
    
          <p class="mt-10 text-center text-sm text-gray-500">
            Already a member?
            {{ ' ' }}
            <router-link :to="{name: 'Login'}" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Login to your account</router-link>
          </p>
        </div>
  </template>
  
  <script setup>
    import store from '../store';
    import {useRouter} from "vue-router";
    import Alert from "../components/Alert.vue";
    import  { ref } from "vue";
  
  const router = useRouter();

  const loading = ref(false);
const errors = ref({});
let errorMsg = ref('')
   
  const user ={
    name: '',
   email: '',
   password: '',
   password_confirmation: ''
  };
  
  function register(ev){
    ev.preventDefault();
    loading.value = true;

    store
      .dispatch('register',user)
        .then(() => {
          loading.value = false;
          router.push({
            name: 'Dashboard'
          });
        })
        .catch(err => {
        loading.value = false;
        errorMsg.value = "Please enter valid input details";
      });
  }
  </script>
    