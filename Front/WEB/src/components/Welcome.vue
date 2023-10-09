<template>
  <div class="wrapper" :style="{ backgroundColor: currentTheme.backgroundColor }">
      <img class="logo" :src="currentLogo"/>
      <div>Welcome to Area</div>
  </div>
</template>

<script>
  import { themes } from '../themes/themes.js';
  import { logo_bleu, logo_gris, logo_vert } from './icons/index';

  export default {
    data() {
      return {
      logo_bleu,
      logo_vert,
      logo_gris,
      backgroundColor: themes.default.backgroundColor,
      };
    },
    created() {
      setTimeout(() => {
        this.showWelcome = false;
        this.$router.push('/login');
      }, 2000);
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
  };
</script>

<style scoped>
.wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100vh;
  color: #FFF;
  font-family: Inter;
  font-size: 64px;
}

.logo {
  width: 200px;
  height: 200px;
}
</style>
