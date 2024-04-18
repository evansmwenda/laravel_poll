<template>
    <PageComponent>
        <template v-slot:header>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                    {{ model.id ? model.title : 'Create New Poll' }}
                </h1>
            </div>
        </template>
        <form @submit.prevent="savePoll">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
                
                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">

                    <!-- image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Image</label>
                        <div class="mt-1 flex items-center">
                            <img 
                            v-if="model.image_url"
                            :src="model.image_url"
                            :alt="model.title"
                            class="w-64 h-48 object-cover"/>
                            <span
                            v-else
                            class="flex items-center justify-center h-12 w-12 
                            rounded-full overflow-hidden bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" 
                                stroke="currentColor" class="w-20 h-20 text-gray-300">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </span>
                            

                            <label for="file-upload" class="ml-3 relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                                <span>Upload a file</span>
                                <input id="file-upload" name="file-upload" type="file"
                                    @change="onFileSelected" class="sr-only">
                            </label>
                        </div>

                    </div>
                    <!-- title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">
                            Title
                        </label>
                        <input type="text" name="title" id="title" 
                        v-model="model.title" autocomplete="poll_title" 
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                    </div>
                    <!-- description  -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3"
                        v-model="model.description" autocomplete="poll_description" placeholder="Describe your poll"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                    </div>
                    <!-- expiry_date  -->
                    <div>
                        <label for="expiry_date" class="block text-sm font-medium text-gray-700">
                            Expiry Date
                        </label>
                        <input type="date" name="expiry_date" id="expiry_date" 
                        v-model="model.expiry_date" autocomplete="poll_expiry_date" 
                        class="mt-1 focus:ring-indigo-500 focus:bo rder-indigo-500 block w-full 
                        shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                    </div>
                    <!-- status  -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="status" id="status" 
                            v-model="model.status" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded
                            shadow-sm sm:text-sm border-gray-300 rounded-md"/>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="status" class="font-medium text-gray-700">
                            Active
                        </label>
                        </div>
                    </div>
                    <!-- Questions  -->
                    <div class="px-4-py-5 bg-white space-y-6 sm:p-6">
                        <h3 class="text-2xl font-semibold flex items-center justify-between">
                            Questions

                            <!-- add new questions  -->
                            <button 
                                type="button"
                                @click="addQuestion()"
                                class="flex items-center text-sm py-1 px-4 rounded-sm text-white bg-gray-600 hover:bg-gray-700"
                                ><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>

                                Add Question
                            </button>
                        </h3>
                        <div v-if="!model.questions.length" class="text-center text-gray-600">
                            You dont have any questions created
                        </div>
                        <div v-for="(question, index) in model.questions" :key="question.id">
                            <QuestionEditor
                                :question="question"
                                :index="index"
                                @change="questionChange"
                                @addQuestion="addQuestion"
                                @deleteQuestion="deleteQuestion"
                                />
                        </div>
                    </div>

                    <!-- submit  -->
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm
                        font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2
                        focus:ring-indigo-500">Save</button>
                    </div>

                </div>
                
            </div>
        </form>
    </PageComponent>
</template>

<script setup>
import { v4 as uuidv4} from "uuid";
import store from '../store';
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router'

import PageComponent from '../components/PageComponent.vue';
import QuestionEditor from '../components/editor/QuestionEditor.vue';

const route = useRoute();
const router = useRouter();

let model = ref({
    title : '',
    status: false,
    description:null,
    image : null,
    expiry_date: null,
    questions:[]
});

if(route.params.id){
    model.value = store.state.polls.find(
        (s) => s.id === parseInt(route.params.id)
    );
}

function onFileSelected(ev){
    const  file = ev.target.files[0];

    const reader = new FileReader();
    reader.onload = () => {
        //send to backend
        model.value.image = reader.result;

        //display
        model.value.image_url = reader.result;
    }
    reader.readAsDataURL(file);
}

function addQuestion(index){
    const newQuestion = {
        id: uuidv4(),
        type: "radio",
        question: "",
        description: null,
        data:{}
    };

    model.value.questions.splice(index,0,newQuestion);
}

function deleteQuestion(question){
    model.value.questions = model.value.questions.filter(
        (q) => q !== question
    )
}

function questionChange(question){
    model.value.questions = model.value.questions.map((q) => {
        if(q.id == question.id){
            return JSON.parse(JSON.stringify(question));
        }
        return q;
    });
}

function savePoll(){
    store.dispatch("savePoll",model.value).then((response) => {
        router.push({
          name: 'SurveyView',
          params: {
            id: response.data.data.id
          }
        })
      })
}
</script>

<style lang="scss" scoped>

</style>