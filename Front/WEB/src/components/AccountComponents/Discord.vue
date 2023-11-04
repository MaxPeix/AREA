<template>
  <div>
    <button @click="connect" v-if="!serviceStates.discord">Se connecter à discord zebi</button>
    <b-switch disabled v-model="serviceStates.discord" class="small-success-button"></b-switch>
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
    connect() {
      const token = localStorage.getItem('token');
      if (!token) {
          this.$router.push('/login');
          return;
      }
      axios.get('http://127.0.0.1:8000/api/discord-callback', {
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
