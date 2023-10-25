<template>
  <div class="wrapper" :style="{ backgroundColor: currentTheme.backgroundColor }">
    <div class="left-content">
        <p class="title">Log in to Area </p>
        <button class="no-acccount-button" @click="movetologin"> No account ?</button>
        <input class="inputs" type="text" placeholder="Email" v-model="emailInput" />
        <div class="password-wrapper">
          <input class="inputs" :type="passwordType" v-model="passwordInput" placeholder="Password" />
          <button class="show-button" @click.prevent="toggleShowPassword">
            {{ showPassword ? 'Hide' : 'Show' }}
          </button>
        </div>
      <b-button :loading="is_loading" @click="movetohome" :disabled="is_loading || emailInput.length < 3 || passwordInput.length < 3" class="button">Log in</b-button>
      <b-button :loading="is_loadingoogle" class="google-button" @click="loginwithgoogle">
        <img :src="google" alt="Google logo" class="google-logo"/>
        <span>Google</span>
      </b-button>
    </div>
    <img class="logo" :src="currentLogo"/>
  </div>
</template>

<script>
  import { themes } from '../themes/themes.js';
  import { logo_bleu, logo_gris, logo_vert, google } from './icons/index';
  import axios from 'axios';

  export default {
    name: 'Login',
    data() {
      return {
        logo_bleu,
        logo_vert,
        logo_gris,
        google,
        backgroundColor: themes.default.backgroundColor,
        password: '',
        showPassword: false,
        emailInput: '',
        passwordInput: '',
        is_loading: false,
        is_loadingoogle: false
      };
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
    },
    mounted() {
      const token = localStorage.getItem('token');
      if (token) {
        this.$router.push('/home');
      }
    },
    methods: {
      loginwithgoogle () {
        this.is_loadingoogle = true;
        axios.get("http://127.0.0.1:8000/api/oauth2callback")
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
      movetologin() {
        this.$router.push('/signup');
      },
      movetohome() {

        const requestData = {
          email: this.emailInput,
          password: this.passwordInput
        };

        this.is_loading = true;
        axios.post("http://localhost:8000/api/login", requestData)
          .then(response => {
            console.log('Réponse du serveur :', response.data);
            localStorage.setItem('token', response.data.authorisation.token);
            this.$router.push('/home');
            this.$buefy.notification.open({
              message: 'Connexion réussie',
              type: 'is-success',
              duration: 5000,
            });
          })
          .catch(error => {
            console.log('Erreur lors de la requête :', error);
            this.$buefy.notification.open({
              message: 'Invalid credentials',
              type: 'is-danger',
              duration: 5000,
            });
          })
          .finally(() => {
            this.is_loading = false;
          });
      }
    },
  };
</script>

<style scoped lang="scss">
.logo {
  width: 237px;
  height: 247px;
  margin-right: 300px;
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
  color : #A7A7A7;
  border: none;
  margin: 10px;
  text-align: center;
  font-family: Inter;
  font-size: 16px;
  font-weight: 500;
  color: #FFF;
  cursor: pointer;
  border-radius: 100px;
  background: #137C8B;
}

.google-button {
  display: flex;
  align-items: center;
  justify-content: center; 
  width: 300px;
  height: 50px;
  color : #000000;
  border: none;
  margin: 10px;
  text-align: center;
  font-family: Inter;
  font-size: 16px;
  font-weight: 500;
  font-weight: bold;
  cursor: pointer;
  border-radius: 100px;
  background: #ffffff;

}

.google-logo {
  width: 20px;
  height: 20px;
  margin-bottom: -4px;
  margin-right: 8px;
  justify-content: center;
  align-items: center;
}

.no-acccount-button {
  background-color: transparent;
  border: none;
  font-size: 18px;
  color : #fff;
  margin-left: auto;
  margin-right: 20px;
  cursor: pointer;
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