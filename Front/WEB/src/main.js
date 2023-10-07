import Vue from 'vue/dist/vue'
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
import Areas from './components/Areas.vue'
import AreaEditor from './components/AreaEditor.vue';
import AreaCreator from './components/AreaCreator.vue';

Vue.config.productionTip = false
Vue.use(Buefy);
Vue.use(VueRouter);

const routes = [
  { path: '/', component: Welcome, name: 'welcome' },
  { path: '/login', component: Login, name: 'login' },
  { path: '/signup', component: Signup, name: 'signup' },
  { path: '/home', component: Home, name: 'home', props: true},
  { path: '/account', component: Account, name: 'account' },
  { path: '/areaeditor', component: AreaEditor, name: 'areaeditor', props: true},
  { path: '/areacreator', component: AreaCreator, name: 'areacreator'},
  { path: '/areas', component: Areas, name: 'areas', props: true},
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
