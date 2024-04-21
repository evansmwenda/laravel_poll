<template>
    <div class="py-5 px-8">
      <div v-if="loading" class="flex justify-center">Loading...</div>
      <form @submit.prevent="submitPoll" v-else class="container mx-auto">
        <div class="grid grid-cols-6 items-center">
          <div class="mr-4">
            <img src="https://placehold.it/150" alt="" />
            <!-- poll.image_url -->
          </div>
          <div class="col-span-5">
            <h1 class="text-3xl mb-3">{{ survey.title }}</h1>
            <p class="text-gray-500 text-sm" v-html="survey.description"></p>
          </div>
        </div>
  
        <div v-if="pollFinished" class="py-8 px-6 bg-emerald-400 text-white w-[600px] mx-auto">
          <div class="text-xl mb-3 font-semibold ">Thank you for participating in this survey.</div>
          <div class="flex items-center justify-between">
            <button @click="submitAnotherResponse" type="button" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Submit another response
          </button>
          <router-link 
            :to="{name: 'Polls'}"
            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            
            Go to Polls</router-link>
          </div>
          
        </div>
        <div v-else>
          <hr class="my-3">
          <div v-for="(question, ind) of survey.questions" :key="question.id">
            <QuestionViewer
              v-model="answers[question.id]"
              :question="question"
              :index="ind"
            />
          </div>
  
          <button
            type="submit"
            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Submit
          </button>
        </div>
      </form>
    </div>
  </template>
  
  <script setup>
  import { computed, ref } from "vue";
  import { useRoute } from "vue-router";
  import { useStore } from "vuex";
  import QuestionViewer from "../components/viewer/QuestionViewer.vue";
  const route = useRoute();
  const store = useStore();
  
  const loading = computed(() => store.state.currentPoll.loading);
  const survey = computed(() => store.state.currentPoll.data);
  
  const pollFinished = ref(false);
  
  const answers = ref({});
  
  store.dispatch("getPollBySlug", route.params.slug);
  
  function submitPoll() {
    store
      .dispatch("savePollAnswer", {
        pollId: survey.value.id,
        answers: answers.value,
      })
      .then((response) => {
        if (response.status === 201) {
          pollFinished.value = true;
        }
      });
  }
  
  function submitAnotherResponse() {
    answers.value = {};
    pollFinished.value = false;
  }
  </script>
  
  <style></style>