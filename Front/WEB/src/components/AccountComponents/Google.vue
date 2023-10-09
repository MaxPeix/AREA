<template>
  <div>
    <button @click="connectGoogle" v-if="!serviceStates.google">Se connecter</button>
    <b-switch disabled v-model="serviceStates.google" class="small-success-button"></b-switch>
  </div>
</template>
  
<script>

import axios from 'axios';

export default {
  props: {
    serviceStates: {},
  },
  data() {
    return {
    };
  },
  methods: {
    connectGoogle() {
      const token = localStorage.getItem('token');
      if (!token) {
          this.$router.push('/login');
          return;
      }
      axios.get('http://127.0.0.1:8000/api/oauth2callback', {
        headers: {
            Authorization: `Bearer ${token}`,
        },
        })
        .then((response) => {
        console.log(response.data);
        window.location.replace(response.data);
        })
        .catch((error) => {
        console.log(error);
      });
    },
  },
};
</script>

<style scoped>
</style>
