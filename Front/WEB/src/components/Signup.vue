<template>
  <div class="wrapper" :style="{ backgroundColor: currentTheme.backgroundColor }">
    <div class="left-content">
        <p class="title">Signup</p>
      <button class="member-button" @click="movetologin">Already a member ?</button>
        <input class="inputs" type="text" placeholder="Username" v-model="usernameInput" @keydown.enter="focusEmail"/>
        <input class="inputs" type="text" placeholder="Email" v-model="emailInput" @keydown.enter="focusPassword" ref="emailInput"/>
        <div class="password-wrapper">
          <input class="inputs" :type="passwordType" v-model="passwordInput" placeholder="Password" @keydown.enter="focusConfirmPassword" ref="passwordInput"/>
            <button class="show-button" @click.prevent="toggleShowPassword">
              {{ showPassword ? 'Hide' : 'Show' }}
            </button>
        </div>
        <div class="password-wrapper">
          <input class="inputs" :type="confirmPasswordType" v-model="confirmPasswordInput" placeholder="Confirm Password" @keydown.enter="movetohome" ref="confirmPasswordInput"/>
            <button class="show-button" @click.prevent="toggleShowConfirmPassword">
              {{ showConfirmPassword ? 'Hide' : 'Show' }}
            </button>
        </div>
      <b-button class="button" :style="{ backgroundColor: currentTheme.buttons }" @click="moveToHome" :loading="loading" :disabled="(this.passwordInput !== this.confirmPasswordInput) || passwordInput.length == 0 || usernameInput.length == 0 || emailInput.length == 0">Signup</b-button>
      <b-button :loading="is_loadingoogle" class="button" :style="{ backgroundColor: currentTheme.buttons }" @click="signupwithgoogle">Sign up with Google</b-button>
    </div>
    <img class="logo" :src="currentLogo"/>
  </div>
  </template>

<script>
  import { themes } from '../themes/themes.js';
  import { logo_bleu, logo_gris, logo_vert } from './icons/index';
  import axios from 'axios';

  export default {
    name: 'Signup',
    data() {
      return {
        logo_bleu,
        logo_vert,
        logo_gris,
        backgroundColor: themes.default.backgroundColor,
        password: '',
        confirmPassword: '',
        showPassword: false,
        showConfirmPassword: false,
        usernameInput: '',
        emailInput: '',
        passwordInput: '',
        confirmPasswordInput: '',
        loading: false,
        is_loadingoogle: false
      };
    },
    mounted() {
      const token = localStorage.getItem('token');
      if (token) {
        this.$router.push('/home');
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
      passwordType() {
        return this.showPassword ? 'text' : 'password';
      },
      confirmPasswordType() {
        return this.showConfirmPassword ? 'text' : 'password';
      },
    },
    methods: {
      focusConfirmPassword(event) {
        if (event.key === 'Enter') {
          this.$refs.confirmPasswordInput.focus();
        }
      },
      focusPassword(event) {
        if (event.key === 'Enter') {
          this.$refs.passwordInput.focus();
        }
      },
      focusEmail(event) {
        if (event.key === 'Enter') {
          this.$refs.emailInput.focus();
        }
      },
      signupwithgoogle () {
        this.is_loadingoogle = true;
        axios.get("http://127.0.0.1:8080/api/oauth2callback")
          .then(response => {
            window.location.href = response.data;
          })
          .finally(() => {
            this.is_loadingoogle = false;
          });
      },
      toggleShowPassword() {
        this.showPassword = !this.showPassword;
      },
      toggleShowConfirmPassword() {
        this.showConfirmPassword = !this.showConfirmPassword;
      },
      movetologin() {
        this.$router.push('/login');
      },
      moveToHome() {
        if (this.passwordInput !== this.confirmPasswordInput) {
          this.$buefy.notification.open({
            message: 'Les mots de passe ne correspondent pas',
            type: 'is-danger',
            duration: 5000,
          });
          return;
        }
        const apiUrl = 'http://localhost:8080/api/register';

        const requestData = {
          username: this.usernameInput,
          email: this.emailInput,
          password: this.passwordInput,
        };
        this.loading = true;
        axios.post(apiUrl, requestData)
          .then(response => {
            localStorage.setItem('token', response.data.authorisation.token);
            this.$buefy.notification.open({
              message: 'Connexion réussie',
              type: 'is-success',
              duration: 5000,
            });
            this.$router.push('/home');
          })
          .catch(error => {
            console.error('Erreur lors de la requête :', error);
            this.$buefy.notification.open({
              message: 'Identifiants incorrects',
              type: 'is-danger',
              duration: 5000,
            });
          })
          .finally(() => {
            this.loading = false;
          });
      }
    },
  };
</script>
  
  <style scoped>
  .logo {
    width: 500px;
    height: 500px;
    margin-right: 300px;
  }

  .member-button {
    background-color: transparent;
    border: none;
    font-size: 18px;
    margin-left: auto;
    margin-right: 20px;
    color: #FFF;
    cursor: pointer;
  }
  .inputs {
    width: 300px;
    height: 50px;
    color : #000000;
    border-radius: 14px;
    border: none;
    margin: 10px;
    padding: 2px 16px;
    text-align: left;
    font-family: Inter;
    font-size: 16px;
    font-weight: 500;
  }
  
  .button {
    width: 300px;
    height: 50px;
    border: none;
    margin: 10px;
    text-align: center;
    font-family: Inter;
    font-size: 16px;
    font-weight: 500;
    color: #FFF;
    cursor: pointer;
    border-radius: 100px;
  }
  
  .title {
    color: #FFF;
    font-family: Inter;
    font-size: 48px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
    margin: 24px 0px;
  }
  .wrapper {
    color: #FFF;
    font-family: Inter;
    font-size: 24px;
    font-weight: 900;
    display: flex;
    align-items: center;
    width: 100%;
    height: 100vh;
    flex-direction: row;
    justify-content: space-between;
  }
  
  .left-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-left: 300px;
  }
  
  .password-wrapper {
    position: relative;
    margin: 10px;
  }
  
  .show-button {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
  }
  
  </style>