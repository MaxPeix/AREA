<template>
    <div class="wrapper" :style="{ backgroundColor: currentTheme.backgroundColor }">
        <div class="columns">
            <div class="column">
                <div class="card" v-for="(area, index) in areas" :key="index">
                    <div class="card-content">{{ area }}</div>
                </div>
                <div class="card">
                    <div class="card-content">+</div>
                </div>
            </div>
            <div class="column is-three-fifths">
                <div class="center">
                    <img class="logo" :src="currentLogo" />
                    <div>See my areas</div>
                </div>
            </div>
            <div class="column">
                <div></div>
            </div>
        </div>
    </div>
        <!-- <img class="pfp" :src="defaultpfp" /> -->
  </template>
    
<script>
import { themes } from '../themes/themes.js';
import logo_bleu from '../components/icons/logo_bleu.png';
import logo_vert from '../components/icons/logo_vert.png';
import logo_gris from '../components/icons/logo_gris.png';
import defaultpfp from '../assets/default_pfp.png';

export default {
		name: 'Home',
    data() {
			return {
				defaultpfp,
				logo_bleu,
				logo_vert,
				logo_gris,
				backgroundColor: themes.light.backgroundColor,
                areas: ["Area 1", "Area 2", "Area 3", "area 5", "area 6"]
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
    },
};
</script>

<style scoped>

.center {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  height: 100%;
}

.on_left {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100vh;
  color: #FFF;
  font-family: Inter;
  font-size: 64px;
}

.wrapper {
  /* width: 100%; */
  height: 100vh;
  color: #FFF;
  font-family: Inter;
  font-size: 64px;
}
.logo {
  width: 200px;
  height: 200px;
}

.pfp {
  width: 61px;
  height: 61px;
  border-radius: 71px;
  margin-bottom: 20px;
	margin-left: auto;
	margin-right: 20px;
}

.scrollable-column {
  height: 100%;
  overflow-y: auto;
}

.card {
  background-color: #fff;
  margin: 10px;
  padding: 20px;
}
</style>
