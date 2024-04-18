import {createStore} from "vuex";
import axiosClient from "../axios";


const store = createStore({
    state:{
        user:{
            data:{},
            token: sessionStorage.getItem('TOKEN')
        },
        dashboard: {
            loading: false,
            data: {}
          },
        surveys: {
            loading: false,
            links: [],
            data: []
          },
        currentPoll: {
            loading: false,
            data: {}
        },
        polls:  {
            loading:false,
            data:[]
        },
        questionTypes: ["text","select","radio","checkbox","textarea"],
        notification: {
            show: false,
            type: 'success',
            message: ''
        }
    },
    getters:{},
    actions:{
        getSurveyBySlug({ commit }, slug) {
            commit("setCurrentPollLoading", true);
            return axiosClient
              .get(`/poll-by-slug/${slug}`)
              .then((res) => {
                commit("setCursetCurrentPollrentSurvey", res.data);
                commit("setCurrentPollLoading", false);
                return res;
              })
              .catch((err) => {
                commit("setCurrentSurvsetCurrentPollLoadingeyLoading", false);
                throw err;
              });
          },
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
        deletePoll({ dispatch },id){
            console.log("deletinggggg "+id);
            return axiosClient.delete(`/polls/${id}`)
            .then((res) =>{
                dispatch('getAllPolls');
                return res;
            })
        },
        getAllPolls({commit}, {url = null} = {}){
            commit("setPollsLoading",true);
            url = url || "/polls";
            return axiosClient.get(url)
                .then((res) => {
                    commit("setPollsLoading",false);
                    commit("setPolls",res.data);
                    return res;
                })
        },
        register({ commit }, user){
            return axiosClient.post(`/register`,user)
            .then(({data}) => {
                commit('setUser',data.user);
                commit('setToken', data.token)
                return data;
            })
        },
        login({ commit }, user){
            return axiosClient.post(`/login`,user)
            .then(({data}) => {
                commit('setUser',data.user);
                commit('setToken', data.token)
                return data;
            })
            .catch(({err}) => {
                console.log("caugt error" + err);
                // commit('dashboardLoading', false)
                throw err;
            })
        },
        logout({ commit }){
            return axiosClient.post(`/logout`)
            .then(response => {
                commit('logout');
                return response;
            })
        },
        getUser({commit}) {
            return axiosClient.get('/user')
            .then(res => {
              console.log(res);
              commit('setUser', res.data)
            })
          },
        getDashboardData({commit}) {
        commit('dashboardLoading', true)
        return axiosClient.get(`/dashboard`)
        .then((res) => {
            commit('dashboardLoading', false)
            commit('setDashboardData', res.data)
            return res;
        })
        .catch(error => {
            commit('dashboardLoading', false)
            return error;
        })
        },
          saveSurveyAnswer({commit}, {pollId, answers}) {
            return axiosClient.post(`/poll/${pollId}/answer`, {answers});
          },
    },
    mutations:{
        logout: (state) => {
            state.user.data = {};
            state.user.token = null;
            sessionStorage.removeItem("TOKEN");
        },
        setUser: (state, user) => {
            state.user.data = user;
        },
        setToken: (state, token) => {
        state.user.token = token;
        sessionStorage.setItem('TOKEN', token);
        },
        dashboardLoading: (state, loading) => {
            state.dashboard.loading = loading;
        },
        setDashboardData: (state, data) => {
            state.dashboard.data = data
        },
        setPollsLoading: (state, loading)=>{
            state.polls.loading = loading;
        },
        setPolls: (state, polls) => {
            state.polls.links = polls.meta.links;
            state.polls.data = polls.data;
        },
        setCurrentPollLoading: (state, loading)=>{
            state.currentPoll.loading = loading;
        },
        setCurrentPoll: (state, poll) => {
            state.currentPoll.data = poll.data;
        },
        notify: (state, {message, type}) => {
            state.notification.show = true;
            state.notification.type = type;
            state.notification.message = message;
            setTimeout(() => {
              state.notification.show = false;
            }, 3000)
        },
    },
    modules:{},
})

export default store;