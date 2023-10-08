<template>
  <div class="overview">
    <div class="overview-header">Overview</div>
    <div class="card-row">
      <!-- Première rangée de cartes -->
      <div class="card">
        <div class="card-content">
          <div class="card-header">
            <p class="area-text">Google</p>
          </div>
          <div class="card-footer">
            <b-switch disabled v-model="serviceStates.google" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-content">
          <div class="card-header">
            <p class="area-text">Discord</p>
          </div>
          <div class="card-footer">
            <b-switch disabled v-model="serviceStates.discord" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-content">
          <div class="card-header">
            <p class="area-text">Twitch</p>
          </div>
          <div class="card-footer">
            <b-switch disabled v-model="serviceStates.twitch" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
    </div>
    <div class="card-row">
      <!-- Deuxième rangée de cartes -->
      <div class="card">
        <div class="card-content">
          <div class="card-header">
            <p class="area-text">Spotify</p>
          </div>
          <div class="card-footer">
            <b-switch disabled v-model="serviceStates.spotify" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-content">
          <div class="card-header">
            <p class="area-text">Youtube</p>
          </div>
          <div class="card-footer">
            <b-switch disabled v-model="serviceStates.youtube" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-content">
          <div class="card-header">
            <p class="area-text">RadioFrance</p>
          </div>
          <div class="card-footer">
            <b-switch disabled v-model="serviceStates.radiofrance" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
    </div>
    <div class="card-row">
      <!-- Troisième rangée de cartes -->
      <div class="card">
        <div class="card-content">
          <div class="card-header">
            <p class="area-text">tmp</p>
          </div>
          <div class="card-footer">
            <b-switch disabled v-model="serviceStates.tmp" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-content">
          <div class="card-header">
            <p class="area-text">tmp</p>
          </div>
          <div class="card-footer">
            <b-switch disabled v-model="serviceStates.tmp" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-content">
          <div class="card-header">
            <p class="area-text">tmp</p>
          </div>
          <div class="card-footer">
            <b-switch disabled v-model="serviceStates.tmp" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import { themes } from '../../themes/themes.js';

export default {
  props: {
    serviceStates: {},
  },
  data() {
    return {
    };
  },
  computed: {
    currentTheme() {
      if (this.backgroundColor === themes.default.backgroundColor) {
        return themes.default;
      } else if (this.backgroundColor === themes.light.backgroundColor) {
        return themes.light;
      } else if (this.backgroundColor === themes.dark.backgroundColor) {
        return themes.dark;
      } else {
        return themes.default;
      }
    },
  },
  methods: {
  },
};
</script>


<style scoped>
.overview {
  display: flex;
  justify-content: center;
  align-items: center;
}

.overview-header {
  font-size: 24px; /* Augmentez la taille de la police si nécessaire */
  margin: 10px; /* Ajoutez un espacement au-dessus du texte "Overview" si nécessaire */
}

.card {
  margin: 10px;
  padding: 20px;
  border-radius: 16px;
  width: 300px; /* Adjust the width as needed */
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: none;
  margin-bottom: 20px;
}

.small-success-button {
  font-size: 20px;
  padding: 2px 4px;
}

.area-text {
  font-size: 18px;
  margin-bottom: 4px;
}

</style>