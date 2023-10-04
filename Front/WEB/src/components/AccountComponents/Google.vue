<template>
    <div>
      <h1>{{ title }}</h1>
      <p>{{ content }}</p>
      <button @click="connect">Se connecter</button>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        title: 'Google',
        content: 'Contenu personnalisé pour l\'aperçu.',
      };
    },
    methods: {
      connect() {
        // Gérez la logique de connexion ici
      },
    },
  };
  </script>
  
  <style scoped>
  /* Ajoutez des styles CSS spécifiques si nécessaire */
  </style>
  