<template>
    <div>
      <h1>{{ title }}</h1>
      <p>{{ content }}</p>
      <button @click="connectGoogle">Se connecter</button>
    </div>
  </template>
  
  <script>

import axios from 'axios';

  export default {
    data() {
      return {
        title: 'Google',
        content: 'Contenu personnalisé pour l\'aperçu.',
      };
    },
    methods: {
        connectGoogle() {
        const token = localStorage.getItem('token');
        if (!token) {
            this.$router.push('/login');
            return; // Arrêter la fonction si le token n'est pas disponible
        }
        axios.get('http://127.0.0.1:8000/api/oauth2callback', {
            headers: {
                Authorization: `Bearer ${token}`,
            },
            })
            .then((response) => {
            console.log(response.data);
            // redirect to url in response.data
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
  