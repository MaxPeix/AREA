<template>
  <div class="overview">
    <div class="card-row">
      <div class="card" :style="{ backgroundColor: currentTheme.bloc2 }">
        <div class="card-content">
          <div class="card-header">
            <img :src="gmail" class="logos">
            <p class="area-text">Google</p>
          </div>
          <div class="card-footer">
            <button class="connect-button" @click="connectGoogle" v-if="!serviceStates.google">Se connecter</button>
            <b-switch v-if="serviceStates.google" disabled v-model="serviceStates.google" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
      <div class="card" :style="{ backgroundColor: currentTheme.bloc2 }">
        <div class="card-content">
          <div class="card-header">
            <img :src="discord" class="logos">
            <p class="area-text">Discord</p>
          </div>
          <div class="card-footer">
            <button class="connect-button" @click="connectDiscord" v-if="!serviceStates.discord">Se connecter</button>
            <b-switch v-if="serviceStates.discord" disabled v-model="serviceStates.discord" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
      <div class="card" :style="{ backgroundColor: currentTheme.bloc2 }">
        <div class="card-content">
          <div class="card-header">
            <img :src="twitch" class="logos">
            <p class="area-text">Twitch</p>
          </div>
          <div class="card-footer">
            <button class="connect-button" @click="connectTwitch" v-if="!serviceStates.twitch">Se connecter</button>
            <b-switch v-if="serviceStates.twitch" disabled v-model="serviceStates.twitch" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
    </div>
    <div class="card-row">
      <div class="card" :style="{ backgroundColor: currentTheme.bloc2 }">
        <div class="card-content">
          <div class="card-header">
            <img :src="spotify" class="logos">
            <p class="area-text">Spotify</p>
          </div>
          <div class="card-footer">
            <button class="connect-button" @click="connectSpotify" v-if="!serviceStates.spotify">Se connecter</button>
            <b-switch v-if="serviceStates.spotify" disabled v-model="serviceStates.spotify" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
      <div class="card" :style="{ backgroundColor: currentTheme.bloc2 }">
        <div class="card-content">
          <div class="card-header">
            <img :src="youtube" class="logos">
            <p class="area-text">Youtube</p>
          </div>
          <div class="card-footer">
            <button class="connect-button" @click="connectYoutube" v-if="!serviceStates.youtube">Se connecter</button>
            <b-switch v-if="serviceStates.youtube" disabled v-model="serviceStates.youtube" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
    </div>
    <div class="card-row">
      <div class="card" :style="{ backgroundColor: currentTheme.bloc2 }">
        <div class="card-content">
          <div class="card-header">
            <img :src="github" class="logos">
            <p class="area-text">GitHub</p>
          </div>
          <div class="card-footer">
            <button class="connect-button" @click="connectGitHub" v-if="!serviceStates.github">Se connecter</button>
            <b-switch v-if="serviceStates.github" disabled v-model="serviceStates.github" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
      </div>
    </div>
</template>

<script>
import { themes } from '../../themes/themes.js'
import { discord, twitch, spotify, youtube, gmail, github } from '../../assets/index'
import axios from 'axios';

export default {
  props: {
    serviceStates: {},
  },
  data() {
    return {
      discord,
      twitch,
      spotify,
      youtube,
      gmail,
      github,
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
  flex-wrap: wrap;
  justify-content: center;
}

.card-row {
  display: flex;
  justify-content: center;
  width: 100%;
}

.card {
  margin: 10px;
  padding: 20px;
  border-radius: 16px;
  flex-basis: calc(33.33% - 20px);
  height: 200px;
  box-sizing: border-box;
}

.card-footer {
  border-top: none;
  justify-content: center;
  display: flex;
  align-items: center;
  flex-direction: column;
  gap: 10px;
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
  justify-content: center;
  align-items: center;
  box-shadow: none;
  margin-bottom: 20px;
}

.small-success-button {
  font-size: 20px;
  padding: 2px 4px;
}

.logos {
  width: 40px;
  height: 40px;
  margin-right: 10px;
  margin-bottom: auto;
  margin-top: auto;
}

.connect-button {
  background-color: #137C8B;
  color: white;
  border: none;
  border-radius: 10px;
  padding: 10px 20px;
  font-size: 16px;
  cursor: pointer;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  transition: background-color 0.3s ease;
}

.connect-button:hover {
  background-color: #145964;
}

.area-text {
  font-size: 18px;
  margin-bottom: 4px;
}

</style>