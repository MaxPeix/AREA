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
            <button class="connect-button" :style="{ backgroundColor: currentTheme.buttons}" @click="connectGoogle" v-if="!serviceStates.google && connectingService !== 'google'">Se connecter</button>
            <div v-if="connectingService === 'google'" class="spinner"></div>
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
            <button class="connect-button" :style="{ backgroundColor: currentTheme.buttons}" @click="connectDiscord" v-if="!serviceStates.discord && connectingService !== 'discord'">Se connecter</button>
            <div v-if="connectingService === 'discord'" class="spinner"></div>
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
            <button class="connect-button" :style="{ backgroundColor: currentTheme.buttons}" @click="connectTwitch" v-if="!serviceStates.twitch && connectingService !== 'twitch'">Se connecter</button>
            <div v-if="connectingService === 'twitch'" class="spinner"></div>
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
            <button class="connect-button" :style="{ backgroundColor: currentTheme.buttons}" @click="connectSpotify" v-if="!serviceStates.spotify && connectingService !== 'spotify'">Se connecter</button>
            <div v-if="connectingService === 'spotify'" class="spinner"></div>
            <b-switch v-if="serviceStates.spotify" disabled v-model="serviceStates.spotify" class="small-success-button"></b-switch>
          </div>
        </div>
      </div>
      <div class="card" :style="{ backgroundColor: currentTheme.bloc2 }">
        <div class="card-content">
          <div class="card-header">
            <img :src="github" class="logos">
            <p class="area-text">GitHub</p>
          </div>
          <div class="card-footer">
            <button class="connect-button" :style="{ backgroundColor: currentTheme.buttons}" @click="connectGitHub" v-if="!serviceStates.github && connectingService !== 'github'">Se connecter</button>
            <div v-if="connectingService === 'github'" class="spinner"></div>
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
      connectingService: null,
      discord,
      twitch,
      spotify,
      youtube,
      gmail,
      github,
      backgroundColor: null,
    };
  },
  mounted() {
    const themeName = localStorage.getItem('theme');
      if (themeName && themes[themeName]) {
        this.backgroundColor = themes[themeName].backgroundColor;
      } else {
        this.backgroundColor = themes.default.backgroundColor;
      }
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
      this.connectingService = 'google';
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
          this.connectingService = null;
          console.log(response.data);
          window.location.replace(response.data);
        })
        .catch((error) => {
          this.connectingService = null;
          console.log(error);
      });
    },
    connectSpotify() {
      this.connectingService = 'spotify';
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
          this.connectingService = null;
          console.log(response.data);
          window.location.replace(response.data);
        })
        .catch((error) => {
          this.connectingService = null;
          console.log(error);
      });
    },
    connectTwitch() {
      this.connectingService = 'twitch';
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
          this.connectingService = null;
          console.log(response.data);
          window.location.replace(response.data);
        })
        .catch((error) => {
          this.connectingService = null;
          console.log(error);
      });
    },
    connectDiscord() {
      this.connectingService = 'discord';
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
          this.connectingService = null;
          console.log(response.data);
          window.location.replace(response.data);
        })
        .catch((error) => {
          $this.connectingService = null;
          console.log(error);
      });
    },
    connectGitHub() {
      this.connectingService = 'github';
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
          this.connectingService = null;
          console.log(response.data);
          window.location.replace(response.data);
        })
        .catch((error) => {
          this.connectingService = null;
          console.log(error);
      });
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
  color: white;
  border: none;
  border-radius: 10px;
  padding: 10px 20px;
  font-size: 16px;
  cursor: pointer;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  transition: background-color 0.3s ease;
}

.area-text {
  font-size: 18px;
  margin-bottom: 4px;
}

.spinner {
  border: 4px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top: 4px solid #fff;
  width: 24px;
  height: 24px;
  animation: spin 1s linear infinite;
  margin-top: 10px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

</style>