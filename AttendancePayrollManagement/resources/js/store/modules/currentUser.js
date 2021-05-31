import axios from 'axios'

const state={
    user:{}
};
const getters={};
const actions={

    getUser({commit}){
        axios.get("/api/current")
        .then(response =>{
            console.log(response);
        commit('setUser', response.data);
        });
       
       
    },
    
    // registerUser({}, user){
    //     axios.post('/api/user/register',{
    //         name:user.name,
    //         email:user.email,
    //         password:user.password,
    //         password_confirmation:user.password_confirmation
    //     })
    //     .then(response =>{
    //         console.log(response.data);
    //         alert('Register successfull');
    //         if(response.data.access_token){
    //             localStorage.setItem('access_token', response.data.access_token);
    //            localStorage.setItem('name', response.data.user.name);
    //         }
    //         window.location.replace('/home');
    //     })
    //     .catch (function(error){
    //         console.log(error);
    //         alert('invalid parameters');
    //     })
    // },

    loginUser({}, user){
        axios.post('/api/user/login',{
            email:user.email,
            password:user.password
        })
        .then(response =>{
            console.log(response.data);
            if(response.data.access_token){
                localStorage.setItem('access_token', response.data.access_token);    
            }
            if (response.data.role=='admin')
            {
                window.location.replace('/admin/dashboard');
            }
            else if (response.data.role=='user')
            {
                window.location.replace('/user/dashboard');
            }
            
            
        })
        .catch(function(error){
                console.log(error);
                alert('Incorrect Credentials');
        })
    },

    logoutUser(){
        	
        axios.post('/api/logout')
            .then(()=>{
                localStorage.removeItem("access_token");
                window.location.replace('/');
                
            })
     
    }
};
const mutations={
    setUser(state, data){
        state.user=data;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}

