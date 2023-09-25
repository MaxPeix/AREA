<template>
  <img v-if="showWelcome" class="logo" src="logo"/>
  <div>
    <div v-if="showWelcome">Welcome to Area</div>
    <RouterView />
  </div>
</template>

<script>
import { RouterView } from 'vue-router';
import logo from './components/icons/logo_vert.png';

export default {
  data() {
    return {
      logo,
      showWelcome: true,
    };
  },
  created() {
    setTimeout(() => {
      this.showWelcome = false;
      this.$router.push('/login');
    }, 2000);
  },
};
</script>

<style scoped>
.logo {
  width: 200px;
  height: 200px;
}

</style>