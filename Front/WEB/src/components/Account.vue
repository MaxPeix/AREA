<template>
  <div class="wrapper" :style="{ backgroundColor: currentTheme.backgroundColor }">
      <img class="arrow" :src="arrow" @click="moveToHome"/>
      <div class="middle-rectangle">
        <p style="color: #A7A7A7; font-size: 24px"> Hello, {{ username }} 👋</p>
      </div>
      <div class="middle-inferior-rectangle">
      </div>
      <div class="left-rectangle">
          <div class="logo-title-container">
              <img class="logo" :src="currentLogo"/>
              <p class="left-rectangle-title">Area Forbidden</p>
          </div>
          <p class="left-rectangle-subtitle">Main Menu</p>
          <div class="apps">
            <div class="app-item">
              <img :src="overview"/>
              <span class="image-name">Overview</span>
            </div>
            <div class="app-item">
              <img :src="discord"/>
              <span class="image-name">Discord</span>
            </div>
            <div class="app-item">
              <img :src="twitch"/>
              <span class="image-name">Twitch</span>
            </div>
            <div class="app-item">
              <img :src="radio_france"/>
              <span class="image-name">Radio France</span>
            </div>
            <div class="app-item">
              <img :src="spotify"/>
              <span class="image-name">Spotify</span>
            </div>
            <div class="app-item">
              <img :src="youtube"/>
              <span class="image-name">Youtube</span>
            </div>
            <div class="app-item">
              <button @click="connectGoogle">
                <img :src="gmail"/>
                <span class="image-name">Gmail</span>
              </button>
            </div>
            <div class="app-item">
              <img :src="google_drive"/>
              <span class="image-name">Drive</span>
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
      backgroundColor: themes.dark.backgroundColor,
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
    moveToHome() {
      this.$router.push('/home');
    },
    connectGoogle() {
      const token = localStorage.getItem('token');
      if (!token) {
        this.$router.push('/login');
        return; // Arrêter la fonction si le token n'est pas disponible
      }
      axios.get('http://127.0.0.1:8000/api/oauth2callback', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        })
        .then((response) => {
          console.log(response.data);
          // redirect to url in response.data
          window.location.replace(response.data);
        })
        .catch((error) => {
          console.log(error);
        });
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
  color: #FFF;
  font-family: Inter;
  font-size: 64px;
}
.middle-rectangle {
  position: absolute;
  margin-left: 25%;
  margin-top: 20px;
  width: 40%;
  height: 10%;
  background-color: #ffffff;
  border: black 2px solid;
  border-radius: 20px;
  align-items: center;
  display: flex;
  justify-content: center;
}
.middle-inferior-rectangle {
  position: absolute;
  margin-left: 25%;
  margin-top: 120px;
  width: 40%;
  height: 30%;
  background-color: #ffffff;
  border: black 2px solid;
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
  background-color: white;
  border: black 2px solid;
  border-radius: 20px;
  flex-direction: column;
  display: flex;
}
.left-rectangle-title {
  color: #A7A7A7;
  font-family: Inter;
  font-size: 24px;
  font-style: normal;
  font-weight: 600;
  line-height: normal;
  margin-top: 25px;
  margin-left: 10px;
}
.left-rectangle-subtitle {
  color: #A7A7A7;
  font-family: Inter;
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
  color: #A7A7A7;
  font-family: Inter;
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

</style>
