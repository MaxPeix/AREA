<template>
  <div class="wrapper" :style="{ backgroundColor: currentTheme.backgroundColor }">
    <img class="arrow" :src="arrow" @click="moveToHome"/>
    <div class="middle-rectangle" :style="{ backgroundColor: currentTheme.bloc2 }">
      <p style="font-size: 24px"> Hello, {{ username }} 👋</p>
    </div>
    <div class="middle-inferior-rectangle">
      <component :is="selectedContentComponent" :serviceStates="serviceStates"></component>
    </div>
    <img :src="logout" class="logout-button" @click="performLogout">
  </div>
</template>

<script>
import { themes } from '../themes/themes.js';
import { logo_bleu, logo_gris, logo_vert } from './icons/index';
import { arrow, logout } from '../assets/index'
import jwt_decode from "jwt-decode";
import axios from 'axios';
import Overview from './AccountComponents/Overview.vue';
import Google from './AccountComponents/Google.vue';
import Spotify from './AccountComponents/Spotify.vue';
import Discord from './AccountComponents/Discord.vue';
import Twitch from './AccountComponents/Twitch.vue';
import Youtube from './AccountComponents/Youtube.vue';
import RadioFrance from './AccountComponents/RadioFrance.vue';

export default {
  name: 'Account',
  data() {
    return {
      logo_bleu,
      logo_vert,
      logo_gris,
      arrow,
      logout,
      username: '',
      email: '',
      backgroundColor: themes.default.backgroundColor,
      selectedContentComponent: null,
      serviceStates: {},
      selectedServiceState: null,
    };
  },
  mounted() {
    const token = localStorage.getItem('token');
    if (!token) {
      this.$router.push('/login');
    } else {
      const decoded = jwt_decode(token);
      const username = decoded.username;
      const email = decoded.email;
      this.username = username;
      this.email = email;
      this.selectedContentComponent = Overview;
      this.getServices();
    }
  },
  computed: {
    currentLogo() {
      if (this.backgroundColor === themes.default.backgroundColor) {
        return this.logo_bleu;
      } else if (this.backgroundColor === themes.light.backgroundColor) {
        return this.logo_vert;
      } else if (this.backgroundColor === themes.dark.backgroundColor) {
        return this.logo_gris;
      } else {
        return this.logo_bleu;
      }
    },
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
    performLogout() {
      localStorage.removeItem('token');
      this.$router.push('/login');
    },
    selectContent(image) {
      const contentMap = {
        overview: Overview,
        google: Google,
        spotify: Spotify,
        discord: Discord,
        twitch: Twitch,
        youtube: Youtube,
        radio_france: RadioFrance,
      };
      this.selectedContentComponent = contentMap[image];
      console.log(selectedContentComponent);
      this.selectedServiceState = this.serviceStates[image];
      console.log(selectedServiceState);
    },
    moveToHome() {
      this.$router.push('/home');
    },
    getServices () {
      const token = localStorage.getItem('token');
      if (!token) {
        this.$router.push('/login');
        return;
      }
      axios.get('http://localhost:8000/api/checktokens', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      .then(response => {
        console.log('Réponse du serveur :', response.data);
        this.serviceStates = response.data;
      })
      .catch(error => {
        console.error('Erreur lors de la récupération des tâches :', error);
      })
    },
  }
};
</script>

<style scoped>
.wrapper {
  display: flex;
  flex-direction: column;
  width: 100%;
  height: 100vh;
  font-family: Inter;
  font-size: 64px;
}
.middle-rectangle {
  position: absolute;
  margin-left: 20%;
  margin-top: 20px;
  width: 60%;
  height: 10%;
  border-radius: 20px;
  align-items: center;
  display: flex;
  justify-content: center;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
}
.middle-inferior-rectangle {
  position: absolute;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
  margin-left: 20%;
  margin-top: 170px;
  width: 60%;
  height: 70%;
  border-radius: 20px;
  align-items: center;
  display: flex;
  justify-content: center;
}
.arrow {
  width: 50px;
  height: 50px;
  margin-bottom: auto;
  margin-right: auto;
  margin-left: 20px;
  cursor: pointer;
  transform: rotate(180deg);
}

.logout-button {
  position: absolute;
  bottom: 10px;
  width: 60px;
  height: 50px;
  margin-right: auto;
  margin-left: 15px;
  font-size: 24px;
  padding: 5px 10px;
  color: #fff;
  border: none;
  cursor: pointer;
  border-radius: 8px;
}

</style>
