<template>
  <nav>
    <RouterLink to="/login" v-if="showNav">Login</RouterLink>
  </nav>
  <RouterView />
</template>

<script>
import { RouterLink, RouterView } from 'vue-router';
export default {
  computed: {
    showNav() {
      return this.$route.path !== '/login'
    },
  },
}
</script>
