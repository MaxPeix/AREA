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
      <button class="button" @click="movetohome">Login</button>
    </div>
    <img class="logo" :src="currentLogo"/>
  </div>
</template>

<script>
  import { themes } from '../themes/themes.js';
  import { logo_bleu, logo_gris, logo_vert } from './icons/index';
  import axios from 'axios';

  export default {
    name: 'Login',
    data() {
      return {
        logo_bleu,
        logo_vert,
        logo_gris,
        backgroundColor: themes.default.backgroundColor,
        password: '',
        showPassword: false,
        emailInput: '',
        passwordInput: '',
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
    methods: {
      toggleShowPassword() {
        this.showPassword = !this.showPassword;
      },
      movetologin() {
        this.$router.push('/signup');
      },
      movetohome() {
        const apiUrl = 'http://localhost:8000/api/login'; // Assurez-vous d'utiliser le bon URL de l'API

        const requestData = {
          email: this.emailInput, // Utilisez la valeur saisie par l'utilisateur
          password: this.passwordInput, // Utilisez le mot de passe saisi par l'utilisateur
        };

        axios.post(apiUrl, requestData)
          .then(response => {
            // Gérer la réponse réussie ici, par exemple, vous pouvez rediriger vers la page d'accueil.
            console.log('Réponse du serveur :', response.data);
            this.$router.push('/home');
          })
          .catch(error => {
            // Gérer les erreurs ici, par exemple, afficher un message d'erreur à l'utilisateur.
            console.error('Erreur lors de la requête :', error);
          });
      }
    },
  };
</script>

<style scoped>
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