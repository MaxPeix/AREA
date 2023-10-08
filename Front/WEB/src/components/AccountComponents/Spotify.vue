<template>
  <div>
    <button @click="connect" v-if="!serviceStates.spotify">Se connecter</button>
    <b-switch disabled v-model="serviceStates.spotify" class="small-success-button"></b-switch>
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
          return; // Arrêter la fonction si le token n'est pas disponible
      }
      axios.get('http://127.0.0.1:8000/api/spotify-callback', {
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
/* Ajoutez des styles CSS spécifiques si nécessaire */
</style>
