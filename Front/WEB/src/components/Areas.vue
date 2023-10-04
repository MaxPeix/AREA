<template>
  <div class="wrapper" :style="{ backgroundColor: currentTheme.backgroundColor }">
    <button class="back-button" @click="movetohome" :style="{ color: currentTheme.buttons }">Retour à l'accueil</button>
    <b-container class="cards-container">
      <b-row v-for="(group, rowIndex) in areaGroups" :key="rowIndex">
        <b-col v-for="(areas, colIndex) in group" :key="colIndex">
          <div class="card" :style="{ backgroundColor: currentTheme.buttons}" @click="moveToAreaEditor(areas.id)">
            <div class="card-content">
              <div class="card-header">
                <p class="area-text">{{ areas.name }}</p>
              </div>
              <div class="card-footer">
                <b-switch :value="true" class="small-success-button"></b-switch>
              </div>
            </div>
          </div>
        </b-col>
      </b-row>
      <div class="card" :style="{ backgroundColor: currentTheme.buttons}" @click="moveToAreaCreator">
        <div class="card-content card-plus">+</div>
      </div>
    </b-container>
    <div class="pfp-container">
      <img :src="defaultpfp" class="pfp" @click="moveToAccount"/>
    </div>
  </div>
</template>

<script>
import { themes } from '../themes/themes.js';
import { logo_bleu, logo_gris, logo_vert } from './icons/index';
import defaultpfp from '../assets/default_pfp.png';

export default {
  name: 'Areas',
  props: ['areas'],
  data() {
    return {
      defaultpfp,
      logo_bleu,
      logo_vert,
      logo_gris,
      backgroundColor: themes.default.backgroundColor,
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
    areaGroups() {
      const groupedAreas = [];
      for (let i = 0; i < this.areas.length; i += 3) {
        groupedAreas.push(this.areas.slice(i, i + 3));
      }
      return groupedAreas.slice(0, 4);
    },
  },
  methods: {
    movetohome() {
      this.$router.push({ name: 'home', params: { areas: this.areas } });
    },
    moveToAccount() {
      this.$router.push('/account');
    },
    moveToAreaEditor(areaId) {
      this.$router.push({ name: 'areaeditor', params: { id: areaId } });
    },
    moveToAreaCreator() {
      this.$router.push('/areacreator');
    },
  }
};
</script>

<style scoped>
  .wrapper {
    /* width: 100%; */
    height: 100vh;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    font-size: 32px;
    font-weight: bold;
    position: relative;
    padding-top: 100px;
  }

  .cards-container {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    margin-left: 120px;
  }
  
  .back-button {
    position: absolute;
    bottom: 20px;
    left: 20px;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
  }

  .card-footer {
    text-align: left;
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    padding: 8px;
  }
  
  .card {
    margin: 10px;
    padding: 20px;
    border-radius: 16px;
    width: 300px;
    height: 180px;
  }
  
  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    box-shadow: none;
    margin-left: -30px;
  }
  

  .small-success-button {
    font-size: 30px;
    padding: 2px 4px;
  }
  
  .card-plus {
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 80px;
    padding: 12px;
    border-radius: 16px;
    margin-top: 0px;
  }

.pfp-container {
  position: absolute;
  bottom: 0;
  right: 0;
  margin: 20px;
}

.pfp {
  width: 100px;
  height: 100px;
}

</style>