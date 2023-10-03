<template>
  <div class="wrapper" :style="{ backgroundColor: currentTheme.backgroundColor }">
    <img class="arrow" :src="arrow" @click="movetohome"/>
    <div class="card" :style="{ backgroundColor: currentTheme.buttons }">
      <div class="card-content">
        <div class="card-header">
          <p class="header-font">Area 1</p>
        </div>
        <div class="card-footer">
          <b-switch :value="true" class="small-success-button"></b-switch>
        </div>
      </div>
    </div>
    <div class="action-reaction-rectangle" :style="{ left:'25%' }">
      <button class="action-reaction-button header-font">Action</button>
    </div>
    <div class="action-reaction-rectangle" :style="{ left: '50%' }">
      <button class="action-reaction-button header-font">Reaction</button>
    </div>
    <div class="logs">
      <p class="header-font" :style="{ alignItems: 'center', display: 'flex', justifyContent: 'center' }">Logs</p>
    </div>
  </div>
</template>

<script>

import { themes } from '../themes/themes.js';
import { arrow } from '../assets/index';

export default {
  name: 'TaskEditor',
  data() {
    return {
      arrow,
      backgroundColor: themes.light.backgroundColor,
    };
  },
  computed: {
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
  methods: {
    movetohome() {
      this.$router.push('/home');
    },
  }
};
</script>

<style scoped>
  .wrapper {
    width: 100%;
    height: 100vh;
    font-size: 32px;
    font-weight: bold;
    position: relative;
    padding-top: 100px;
  }
  .header-font {
    font-size: 32px;
    font-weight: bold;
    color: black;
  }
  .arrow {
    position: absolute;
    top: 0;
    margin-left: 60px;
    width: 100px;
    height: 100px;
    padding: 20px;
    transform:rotate(180deg);
    cursor: pointer;
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
    margin-left: 75px;
    border-radius: 16px;
    width: 300px;
    height: 180px;
  }
  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-left: -30px;
    box-shadow: none;
  }
  .small-success-button {
    font-size: 30px;
    padding: 2px 4px;
  }
  .action-reaction-rectangle {
    position: absolute;
    top: 50%;
    width: 300px;
    height: 150px;
    background-color: #9FCDA8;
    border-radius: 20px;
    align-items: center;
    display: flex;
    justify-content: center;
  }
  .action-reaction-button {
    background-color: transparent;
    border: none;
    cursor: pointer;
  }
  .logs {
    position: absolute;
    top: 20px;
    right: 15px;
    width: 300px;
    height: 700px;
    background-color: #9FCDA8;
    border-radius: 16px;
  }
</style>