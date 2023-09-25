  <template>
    <img :src="theme.logo" class="logo"/>
    <p>Hello World!</p>
  </template>

<script>
import { themes } from '../themes/themes.js';

export default {
  name: 'Login',
  data() {
    return {
      theme: themes.default,
    };
  },
};
</script>

<style scoped>
.logo {
  width: 100px;
  height: 100px;
}

</style>