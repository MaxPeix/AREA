<template>
  <div>
    <button @click="connect">Se connecter</button>
    <b-switch v-model="serviceStates.youtube" class="small-success-button"></b-switch>
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
      },
  },
};
</script>

<style scoped>
/* Ajoutez des styles CSS spécifiques si nécessaire */
</style>
