<template>
  <div class="wrapper" :style="{ backgroundColor: currentTheme.backgroundColor }">
    <img class="arrow" :src="arrow" @click="moveToTasks"/>
    <div class="middle-rectangle" :style="{ backgroundColor: currentTheme.bloc2 }">
      <p style="font-size: 24px"> Hello, {{ username }} 👋</p>
    </div>
    <div class="middle-inferior-rectangle" :style="{ backgroundColor: currentTheme.bloc2 }">
      <component :is="selectedContentComponent" :serviceStates="serviceStates"></component>
    </div>
    <div class="left-rectangle" :style="{ backgroundColor: currentTheme.bloc2 }">
      <div class="logo-title-container">
        <img class="logo" :src="currentLogo"/>
        <p class="left-rectangle-title">Area Forbidden</p>
      </div>
      <div class="apps">
        <div class="app-item" @click="selectContent('overview')">
          <img :src="overview"/>
          <span class="image-name">Overview</span>
        </div>
        <div class="app-item" @click="selectContent('google')">
          <img :src="gmail"/>
          <span class="image-name">Google</span>
        </div>
        <div class="app-item" @click="selectContent('discord')">
          <img :src="discord"/>
          <span class="image-name">Discord</span>
        </div>
        <div class="app-item" @click="selectContent('twitch')">
          <img :src="twitch"/>
          <span class="image-name">Twitch</span>
        </div>
        <div class="app-item" @click="selectContent('spotify')">
          <img :src="spotify"/>
          <span class="image-name">Spotify</span>
        </div>
        <div class="app-item" @click="selectContent('youtube')">
          <img :src="youtube"/>
          <span class="image-name">Youtube</span>
        </div>
        <div class="app-item" @click="selectContent('radio_france')">
          <img :src="radio_france"/>
          <span class="image-name">RadioFrance</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { themes } from '../themes/themes.js';
import { logo_bleu, logo_gris, logo_vert } from './icons/index';
import { arrow, overview, discord, twitch, radio_france, spotify, youtube, gmail, google_drive } from '../assets/index'
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
      overview,
      discord,
      twitch,
      radio_france,
      spotify,
      youtube,
      gmail,
      google_drive,
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
    moveToTasks() {
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
  margin-left: 25%;
  margin-top: 20px;
  width: 40%;
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
  margin-left: 25%;
  margin-top: 200px;
  width: 60%;
  height: 70%;
  border-radius: 20px;
  align-items: center;
  display: flex;
  justify-content: center;
}
.left-rectangle {
  position: absolute;
  margin-left: 20px;
  top: 10%;
  width: 20%;
  height: 80%;
  border-radius: 20px;
  flex-direction: column;
  display: flex;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
}
.left-rectangle-title {
  font-size: 24px;
  font-style: normal;
  font-weight: 600;
  line-height: normal;
  margin-top: 25px;
  margin-left: 10px;
}
.left-rectangle-subtitle {
  font-size: 16px;
  font-style: normal;
  font-weight: 600;
  line-height: normal;
  margin-top: 14px;
  margin-left: 20px;
}
.apps {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 20px;
  margin-right: auto;
  margin-left: 20px;
  gap: 15px;
  height: 50px;
  width: 50px;
  cursor: pointer;
}
.app-item {
  display: flex;
  align-items: center;
  gap: 20px;
  height: 50px;
  width: 50px;
}
.image-name {
  font-size: 20px;
  font-style: normal;
  font-weight: 600;
  line-height: normal;
}
.logo {
  width: 60px;
  height: 60px;
  margin-top: 15px;
}
.logo-title-container {
  display: flex;
  flex-direction: row;
  align-items: center;
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

.small-success-button {
  font-size: 30px; /* Ajustez la taille de la police selon vos préférences */
  padding: 2px 4px; /* Ajustez le rembourrage selon vos préférences */
}

</style>
