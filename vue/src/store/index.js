import {createStore} from "vuex";
import axiosClient from "../axios";


const store = createStore({
    state:{
        user:{
            data:{},
            token: sessionStorage.getItem('TOKEN')
        },
        currentPoll: {
            loading: false,
            data: {}
        },
        polls:  {
            loading:false,
            data:[]
        },
        questionTypes: ["text","select","radio","checkbox","textarea"]
    },
    getters:{},
    actions:{
        getPoll( { commit },id ){
            commit("setCurrentPollLoading",true);
            return axiosClient.get(`/polls/${id}`)
                .then((res) => {
                    commit("setCurrentPoll",res.data);
                    commit("setCurrentPollLoading",false);
                    return res;
                })
                .catch((err) => {
                    commit("setCurrentPollLoading",false);
                    throw err;
                });
        },
        savePoll({ commit },poll){
            delete poll.image_url;
            
            let response;
            if(poll.id){
                //we are updating
                response  = axiosClient.put(`/polls/${poll.id}`,poll)
                .then((res) => {
                    commit("setCurrentPoll",res.data);
                    return res;
                });
                
            }else{
                //creating new
                response  = axiosClient.post(`/polls`,poll)
                .then((res) => {
                    commit("setCurrentPoll",res.data);
                    return res;
                });
                
            }
            return response;
        },
        deletePoll({},id){
            console.log("deletinggggg "+id);
            return axiosClient.delete(`/polls/${id}`);
        },
        getAllPolls({commit}){
            commit("setPollsLoading",true);
            return axiosClient.get('/polls')
                .then((res) => {
                    commit("setPollsLoading",false);
                    commit("setPolls",res.data);
                    return res;
                })
        },
        register({ commit }, user){
            return axiosClient.post(`/register`,user)
            .then(({data}) => {
                commit('setUser',data);
                return data;
            })
        },
        login({ commit }, user){
            return axiosClient.post(`/login`,user)
            .then(({data}) => {
                commit('setUser',data);
                return data;
            })
        },
        logout({ commit }){
            return axiosClient.post(`/logout`)
            .then(response => {
                commit('logout');
                console.log("logging out");
                console.log(response);
                return response;
            })
        }
    },
    mutations:{
        setPollsLoading: (state, loading)=>{
            state.polls.loading = loading;
        },
        setPolls: (state, polls) => {
            state.polls.data = polls.data;
        },
        setCurrentPollLoading: (state, loading)=>{
            state.currentPoll.loading = loading;
        },
        setCurrentPoll: (state, poll) => {
            state.currentPoll.data = poll.data;
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