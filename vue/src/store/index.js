import {createStore} from "vuex";
import axiosClient from "../axios";
const tmpPolls = 
[
    {
    id:1,
    title: 'The best channel',
    slug: 'the-best-channel',
    status: 'draft',
    image: 'https://placehold.it/120',
    description: 'I am a web developer lorem ipsum dolerum with 9 years experience',
    created_at: "2024-04-18 20:00:00",
    updated_at: "2024-04-18 20:00:00",
    expiry_date: "2024-05-01 20:00:00",
    questions:[
        {
            id: 1,
            type: 'radio',
            question: 'From which continent are you?',
            description: null,
            data:{
                options: [
                    {
                        uuid: '861864b2-bfb9-4f88-8282-49926830a76a',
                        text: 'Africa'
                    },
                    {
                        uuid: '861864b9-bfb9-4f88-8282-49926830a76a',
                        text: 'America'
                    },
                    {
                        uuid: '861864b8-bfb9-4f88-8282-49926830a76a',
                        text: 'Asia'
                    },
                ]
            }
        },
        {
            id: 2,
            type: 'radio',
            question: 'Which language videos do you want to see on my website?',
            description: null,
            data:{
                options: [
                    {
                        uuid: '861864b2-bfb9-4f88-8282-49926830a76a',
                        text: 'Spring Boot'
                    },
                    {
                        uuid: '861864b9-bfb9-4f88-8282-49926830a76a',
                        text: 'Flutter BLoC'
                    },
                    {
                        uuid: '861864b8-bfb9-4f88-8282-49926830a76a',
                        text: 'MicroServices Architecture'
                    },
                ]
            }
        },
        {
            id: 3,
            type: 'radio',
            question: 'Which PHP framework do you prefer?',
            description: null,
            data:{
                options: [
                    {
                        uuid: '8633864b2-bfb9-4f88-8282-49926830a76a',
                        text: 'Laravel'
                    },
                    {
                        uuid: '863864b9-bfb9-4f88-8282-49926830a76a',
                        text: 'CodeIgniter'
                    },
                    {
                        uuid: '866864b8-bfb9-4f88-8282-49926830a76a',
                        text: 'Vanilla'
                    },
                ]
            }
        },
    ]
}
];

const store = createStore({
    state:{
        user:{
            data:{},
            token: sessionStorage.getItem('TOKEN')
        },
        polls:  [...tmpPolls],
        questionTypes: ["text","select","radio","checkbox","textarea"]
    },
    getters:{},
    actions:{
        savePoll({ commit },poll){
            delete poll.image_url;
            
            let response;
            if(poll.id){
                //we are updating
                response  = axiosClient.put("/polls/${poll.id}",poll)
                .then((res) => {
                    commit("updatePoll",res.data);
                    return res;
                });
                
            }else{
                //creating new
                response  = axiosClient.post("/polls",poll)
                .then((res) => {
                    commit("savePoll",res.data);
                    return res;
                });
                
            }
            return response;
        },
        register({ commit }, user){
            return axiosClient.post('/register',user)
            .then(({data}) => {
                commit('setUser',data);
                return data;
            })
        },
        login({ commit }, user){
            return axiosClient.post('/login',user)
            .then(({data}) => {
                commit('setUser',data);
                return data;
            })
        },
        logout({ commit }){
            return axiosClient.post('/logout')
            .then(response => {
                commit('logout');
                console.log("logging out");
                console.log(response);
                return response;
            })
        }
    },
    mutations:{
        savePoll: (state,poll) => {
           state.polls = [...state.polls, poll.data]
        },
        updatePoll: (state,poll) => {
            state.polls = state.polls.map((s) => {
                if(s.id == poll.data.id){
                    return poll.data;
                }
                return s;
            });
           
        },
        logout: (state) => {
            state.user.data = {};
            state.user.token = null;
        },
        setUser: (state, userData) => {
            state.user.token = userData.token,
            state.user.data = userData.data;
            sessionStorage.setItem('TOKEN', userData.token);
        }
    },
    modules:{},
})

export default store;