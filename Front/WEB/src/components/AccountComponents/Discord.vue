<template>
  <div>
    <button @click="connect" v-if="!serviceStates.discord">Se connecter</button>
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
      },
  },
};
</script>

<style scoped>
/* Ajoutez des styles CSS spécifiques si nécessaire */
</style>
