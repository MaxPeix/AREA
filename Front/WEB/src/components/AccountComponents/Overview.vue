<template>
  <div class="overview">
    <!-- <div class="overview-header">Overview</div> -->
    <div class="card-row">
      <div class="card" :style="{ backgroundColor: currentTheme.bloc2 }">
        <div class="card-content">
          <div class="card-header">
            <p class="area-text">Google</p>
          </div>
          <div class="card-footer">
            <button @click="connectGoogle" v-if="!serviceStates.google">Se connecter</button>
            <b-switch disabled v-model="serviceStates.google" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
      <div class="card" :style="{ backgroundColor: currentTheme.bloc2 }">
        <div class="card-content">
          <div class="card-header">
            <p class="area-text">Discord</p>
          </div>
          <div class="card-footer">
            <button @click="connectDiscord" v-if="!serviceStates.discord">Se connecter</button>
            <b-switch disabled v-model="serviceStates.discord" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
      <div class="card" :style="{ backgroundColor: currentTheme.bloc2 }">
        <div class="card-content">
          <div class="card-header">
            <p class="area-text">Twitch</p>
          </div>
          <div class="card-footer">
            <button @click="connectTwitch" v-if="!serviceStates.twitch">Se connecter</button>
            <b-switch disabled v-model="serviceStates.twitch" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
    </div>
    <div class="card-row">
      <div class="card" :style="{ backgroundColor: currentTheme.bloc2 }">
        <div class="card-content">
          <div class="card-header">
            <p class="area-text">Spotify</p>
          </div>
          <div class="card-footer">
            <button @click="connectSpotify" v-if="!serviceStates.spotify">Se connecter</button>
            <b-switch disabled v-model="serviceStates.spotify" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
      <div class="card" :style="{ backgroundColor: currentTheme.bloc2 }">
        <div class="card-content">
          <div class="card-header">
            <p class="area-text">Youtube</p>
          </div>
          <div class="card-footer">
            <button @click="connectYoutube" v-if="!serviceStates.youtube">Se connecter</button>
            <b-switch disabled v-model="serviceStates.youtube" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
    <div class="card-row">
      <div class="card" :style="{ backgroundColor: currentTheme.bloc2 }">
        <div class="card-content">
          <div class="card-header">
            <p class="area-text">GitHub</p>
          </div>
          <div class="card-footer">
            <button @click="connectGitHub" v-if="!serviceStates.github">Se connecter</button>
            <b-switch disabled v-model="serviceStates.github" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
      <div class="card" :style="{ backgroundColor: currentTheme.bloc2 }">
        <div class="card-content">
          <div class="card-header">
            <p class="area-text">tmp</p>
          </div>
          <div class="card-footer">
            <b-switch disabled v-model="serviceStates.tmp" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
      <div class="card" :style="{ backgroundColor: currentTheme.bloc2 }">
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
  </div>
</template>

<script>

import { themes } from '../../themes/themes.js';
import axios from 'axios';

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
    connectSpotify() {
      const token = localStorage.getItem('token');
      if (!token) {
          this.$router.push('/login');
          return;
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
    connectTwitch() {
      const token = localStorage.getItem('token');
      if (!token) {
          this.$router.push('/login');
          return;
      }
      axios.get('https://127.0.0.1:8000/api/twitch-callback', {
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
    connectDiscord() {
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
    connectGitHub() {
      const token = localStorage.getItem('token');
      if (!token) {
          this.$router.push('/login');
          return;
      }
      axios.get('http://127.0.0.1:8000/api/github-callback', {
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
    connectYoutube() {

    },
    connectRadioFrance() {

    },
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
  font-size: 24px;
  margin: 10px;
}

.card-footer {
  border-top: none;
}

.card {
  margin: 10px;
  padding: 20px;
  border-radius: 16px;
  width: 400px;
  height: 200px;
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