<template>
  <div class="wrapper" :style="{ backgroundColor: currentTheme.backgroundColor }">
    <img class="arrow" :src="arrow" @click="moveToHome"/>
    <div class="middle-rectangle" :style="{ backgroundColor: currentTheme.bloc2 }">
      <p style="font-size: 24px"> Hello, {{ username }} 👋</p>
    </div>
    <div class="middle-inferior-rectangle">
      <component :is="selectedContentComponent" :serviceStates="serviceStates" :loadingCheckTokens="loadingCheckTokens"></component>
    </div>
    <img :src="logout" class="logout-button" @click="performLogout">
    <div class="theme-button" @click="toggleThemeMenu">
      <img :src="theme_logo" alt="Theme" />
    </div>
    <div class="theme-menu" v-if="showThemeMenu">
      <div class="theme-color" @click="changeTheme('default')" style="background-color: #709CA7;"></div>
      <div class="theme-color" @click="changeTheme('dark')" style="background-color: #585858;"></div>
      <div class="theme-color" @click="changeTheme('light')" style="background-color: #7DC2A5;"></div>
    </div>
  </div>
</template>7DC2A5

<script>
import { themes } from '../themes/themes.js';
import { logo_bleu, logo_gris, logo_vert } from './icons/index';
import { arrow, logout, theme_logo } from '../assets/index'
import jwt_decode from "jwt-decode";
import axios from 'axios';
import Overview from './AccountComponents/Overview.vue';

export default {
  name: 'Account',
  data() {
    return {
      logo_bleu,
      logo_vert,
      logo_gris,
      arrow,
      theme_logo,
      logout,
      username: '',
      email: '',
      backgroundColor: null,
      selectedContentComponent: null,
      serviceStates: {},
      selectedServiceState: null,
      showThemeMenu: false,
      loadingCheckTokens: false,
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
    const themeName = localStorage.getItem('theme');
    if (themeName && themes[themeName]) {
      this.backgroundColor = themes[themeName].backgroundColor;
    } else {
      this.backgroundColor = themes.default.backgroundColor;
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
    toggleThemeMenu() {
      this.showThemeMenu = !this.showThemeMenu;
    },
    changeTheme(themeName) {
      localStorage.setItem('theme', themeName);
      this.backgroundColor = themes[themeName].backgroundColor;
      this.showThemeMenu = false;
      window.location.reload();
    },
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
        dropbox: dropbox,
        youtube: Youtube,
        github: Github,
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
      this.loadingCheckTokens = true;
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
      .finally(() => {
        this.loadingCheckTokens = false;
      });
    },
  },
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

.theme-button img {
  width: 30px;
  height: 30px;
  position: absolute;
  top: 10px;
  right: 10px;
  cursor: pointer;
}

.theme-menu {
  position: absolute;
  top: 40px;
  right: 10px;
  display: flex;
  flex-direction: column;
  align-items: center;
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: 5px;
  padding: 10px;
  z-index: 1;
}

.theme-color {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  margin: 5px;
  cursor: pointer;
}

</style>
