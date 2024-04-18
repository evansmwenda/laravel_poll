<template>
<PageComponent>
  <template v-slot:header>
    <div class="flex items-center justify-between">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">Surveys</h1>
      <router-link 
      :to="{name: 'SurveyCreate'}"
      class="py-2 px-3 text-white bg-emerald-500 text-sm rounded-md hover:bg-emerald-600">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" 
      viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mt-1 inline-block">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
      </svg>
      Create New Poll</router-link>
    </div>
    
  </template>
  <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3">
    <PollListItem 
      v-for="poll in polls" 
      :key="poll.id" 
      :poll="poll"
      @delete="deletePoll(poll)"
    />
  </div>
</PageComponent>
</template>

<script setup>
import store from '../store';
import { computed } from 'vue';
import PageComponent from '../components/PageComponent.vue';
import { useRouter } from 'vue-router';
import PollListItem from '../components/PollListItem.vue';

const polls = computed(() => store.state.polls.data)
const router = useRouter();

store.dispatch('getAllPolls')
 
function deletePoll(poll){
  if(confirm(`Are you sure you want to delete this poll? Operation can't be undone!!`)){
    //go ahead and delete survey
    store.dispatch('deletePoll',poll.id)
      .then(() => {
            store.dispatch('getPolls')
        });
  }
}
</script>