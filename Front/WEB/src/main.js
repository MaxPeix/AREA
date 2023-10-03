import Vue from 'vue'
import App from './App.vue'
import Buefy from 'buefy'
import 'buefy/dist/buefy.css'
import VueRouter from 'vue-router';
// import 'bootstrap-vue/dist/bootstrap-vue.css';
// import BootstrapVue from 'bootstrap-vue';
import Login from './components/Login.vue'
import Welcome from './components/Welcome.vue'
import Signup from './components/Signup.vue'
import Home from './components/Home.vue'
import Account from './components/Account.vue'
import Tasks from './components/Tasks.vue'

Vue.config.productionTip = false
Vue.use(Buefy);
Vue.use(VueRouter);
// Vue.use(BootstrapVue);

const routes = [
  { path: '/', component: Welcome, name: 'welcome' },
  { path: '/login', component: Login, name: 'login' },
  { path: '/signup', component: Signup, name: 'signup' },
  { path: '/home', component: Home, name: 'home', props: true},
  { path: '/account', component: Account, name: 'account' },
  { path: '/tasks', component: Tasks, name: 'tasks', props: true},
  { path: '/:pathMatch(.*)*', redirect: '/' },
];

const router = new VueRouter({
  routes,
  mode: 'history'
});

new Vue({
  render: h => h(App),
  router
}).$mount('#app')
