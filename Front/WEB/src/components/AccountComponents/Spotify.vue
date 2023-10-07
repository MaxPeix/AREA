<template>
    <div>
      <h1>{{ title }}</h1>
      <p>{{ content }}</p>
      <button @click="connect">Se connecter</button>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  export default {
    data() {
      return {
        title: 'Spotify',
        content: 'Contenu personnalisé pour l\'aperçu.',
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
  