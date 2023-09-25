  <template>
    <img :src="logo" alt="Logo" class="logo"/>
    <p>Hello World! {{ test }}</p>
  </template>

<script>
import logo from './icons/logo_vert.png';

export default {
  name: 'Login',
  data() {
    return {
      logo,
      test: 'test',
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