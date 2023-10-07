<template>
  <form action="">
      <div class="modal-card" style="width: 700px">
          <header class="modal-card-head">
              <p class="modal-card-title">Create an area</p>
          </header>
          <section class="modal-card-body">
              <b-field label="Name">
                <b-input v-model="formData.name" type="name" placeholder="Name of the area" required></b-input>
              </b-field>
              <b-field label="Description">
                <b-input v-model="formData.description" type="description" placeholder="Description of the area" required></b-input>
              </b-field>
              <b-field label="Action">
                <b-select v-model="formData.selectedAction" placeholder="Select an action">
                  <option v-for="action in currentActions" :value="action">{{ action.serviceName }}</option>
                </b-select>
              </b-field>
              <b-field label="Reaction">
                <b-select v-model="formData.selectedReaction" placeholder="Select a reaction">
                  <option v-for="reaction in currentReactions" :value="reaction">{{ reaction.serviceName }}</option>
                </b-select>
              </b-field>
          </section>
          <footer class="modal-card-foot">
            <b-button
              label="Close"
              type="is-danger"
              @click="closeModal"
              v-if="canClose"
            />
            <b-button
              label="Create"
              type="is-primary"
              @click="createArea" />
          </footer>
      </div>
  </form>
</template>

<script>
import axios from 'axios';


export default {
  props: {
    canClose: Boolean,
  },
  mounted() {
    const token = localStorage.getItem('token');
    if (!token) {
      this.$router.push('/login');
    }
    this.getServices();
  },
  computed: {
    currentReactions() {
        return this.reactions;
    },
    currentActions() {
        return this.actions;
    },
  },
  data() {
    return {
      formData: {
        name: "",
        description: "",
        selectedAction: "",
        selectedReaction: "",
      },
      actions: [],
      reactions: [],
      services: []
    };
  },
  methods: {
    closeModal() {
      this.$emit('close'); // Émet un événement "close" pour indiquer à Home de fermer le composant modal.
    },
    async createArea() {
      console.log(this.formData.name);
      console.log(this.formData.description);
      console.log(this.formData.selectedAction);
      console.log(this.formData.selectedReaction);
      const token = localStorage.getItem('token');
      if (!token) {
        this.$router.push('/login');
        return;
      }
      let area_new_id = "";
      let action_new_id = "";
      const apiUrl = 'http://localhost:8000/api/area';
      const requestData = {
        name: this.formData.name,
        description: this.formData.description,
        activated: "true"
      };
      try {
        /* Area */
        const response = await axios.post(apiUrl, requestData, {
          headers: {
              Authorization: `Bearer ${token}`
          },
        })
        console.log('Réponse du serveur :', response.data);
        area_new_id = response.data.id;

        /* Action */
        const apiUrlAction = 'http://localhost:8000/api/actions/' + area_new_id;
        const requestDataAction = {
          services_id: this.formData.selectedAction.services_id,
          activated: "true"
        };
        console.log(apiUrlAction);

        const response2 = await axios.post(apiUrlAction, requestDataAction, {
          headers: {
              Authorization: `Bearer ${token}`
          },
        })
        console.log('Réponse du serveur 2 :', response2.data);
        action_new_id = response2.data.id;

        /* Reaction */
        const apiUrlReaction = 'http://localhost:8000/api/reactions/' + area_new_id;
        const requestDataReaction = {
          actions_id: action_new_id,
          services_id: this.formData.selectedReaction.services_id,
          activated: "true"
        };
        const response3 = await axios.post(apiUrlReaction, requestDataReaction, {
          headers: {
              Authorization: `Bearer ${token}`
          },
        })
        console.log('Réponse du serveur 3 :', response3.data);
        closeModal();
      } catch (error) {
        console.error(error)
      }
    },
    getServices() {
      const token = localStorage.getItem('token');
      if (!token) {
        this.$router.push('/login');
        return;
      }
      axios.get('http://localhost:8000/api/services', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      .then(response => {
        this.services = response.data;
        return Promise.all([this.getActions(), this.getReactions()]); // Appel asynchrone des deux fonctions
      })
      .catch(error => {
        console.error('Erreur lors de la récupération des services :', error);
      })
      .finally(() => {
        // Cacher le spinner de chargement
      });
    },
    getActions() {
      const token = localStorage.getItem('token');
      if (!token) {
        this.$router.push('/login');
        return;
      }
      axios.get('http://localhost:8000/api/actions', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      .then(response => {
        console.log('Réponse du serveur :', response.data);
        this.actions = response.data.map(action => ({
          ...action,
          serviceName: this.findServiceName(action.services_id),
        }));
        console.log("actions : ", this.actions)
      })
      .catch(error => {
        console.error('Erreur lors de la récupération des tâches :', error);
      })
      .finally(() => {
        // Cacher le spinner de chargement
      });
    },
    getReactions() {
      const token = localStorage.getItem('token');
      if (!token) {
        this.$router.push('/login');
        return;
      }
      axios.get('http://localhost:8000/api/reactions', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      .then(response => {
        console.log('Réponse du serveur reactions:', response.data);
        this.reactions = response.data.map(reaction => ({
          ...reaction,
          serviceName: this.findServiceName(reaction.services_id),
        }));
        console.log("reactions: ", this.reactions)
      })
      .catch(error => {
        console.error('Erreur lors de la récupération des tâches :', error);
      })
      .finally(() => {
        // Cacher le spinner de chargement
      });
    },
    findServiceName(serviceId) {
      const service = this.services.find(s => s.id === serviceId);
      return service ? service.service_name : 'Service non trouvé';
    },
  },
}
</script>