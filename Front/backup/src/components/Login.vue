<template>
  <div class="wrapper" :style="{ backgroundColor: currentTheme.backgroundColor }">
    <div class="left-content">
        <p class="title">Log in to Area </p>
        <input class="inputs" type="text" placeholder="Email" />
          <div class="password-wrapper">
            <input class="inputs" :type="passwordType" v-model="password" placeholder="Password" />
            <button class="show-button" @click.prevent="toggleShowPassword">
              {{ showPassword ? 'Hide' : 'Show' }}
            </button>
          </div>
      <button class="button">Login</button>
    </div>
    <img class="logo" :src="currentLogo"/>
  </div>
</template>

<script>
import { themes } from '../themes/themes.js';
import logo_bleu from '../components/icons/logo_bleu.png';
import logo_vert from '../components/icons/logo_vert.png';
import logo_gris from '../components/icons/logo_gris.png';

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
  text-align: center;
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

.title {
  color: #FFF;
  font-family: Inter;
  font-size: 48px;
  font-style: normal;
  font-weight: 600;
  line-height: normal;
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