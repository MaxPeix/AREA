<template>
  <div>
    <button @click="connect" v-if="!serviceStates.twitch">Se connecter</button>
    <b-switch disabled v-model="serviceStates.twitch" class="small-success-button"></b-switch>
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
</style>
