<template>
  <form action="">
      <div class="modal-card" style="width: 700px">
          <header class="modal-card-head">
              <p class="modal-card-title">Create an area</p>
          </header>
          <section class="modal-card-body">
              <b-field label="Name">
                <b-input v-model="name" type="name" placeholder="Name of the area" required></b-input>
              </b-field>
              <b-field label="Description">
                <b-input v-model="description" type="description" placeholder="Description of the area" required></b-input>
              </b-field>
              <b-field label="Action">
                <b-select v-model="selectedAction" placeholder="Select an action">
                  <option v-for="service in services" :value="service" v-if="service.service_name.includes('[ACTION]')">{{ service.service_name }}</option>
                </b-select>
              </b-field>
              <b-field label="Reactions">
                <b-select v-model="selectedReaction" placeholder="Select a reaction">
                  <option v-for="service in services" :value="service" v-if="service.service_name.includes('[REACTION]')">{{ service.service_name }}</option>
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
              :loading="loading"
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
  data() {
    return {
      name: null,
      description: null,
      selectedAction: null,
      selectedReaction: null,
      services: [],
      loading: false,
    };
  },
  methods: {
    closeModal() {
      this.$emit('close');
    },
    createReaction(area_id, service_id, token, actions_id_created) {
      axios.post('http://localhost:8000/api/reactions/' + area_id, {
        services_id: service_id,
        actions_id: actions_id_created,
        activated: true
      }, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      .then(response => {
        console.log(response.data);
      })
      .catch(error => {
        console.error('Erreur lors de la création de la réaction :', error);
      })
    },
    createAction(area_id, service_id, token) {
      axios.post('http://localhost:8000/api/actions/' + area_id, {
        services_id: service_id,
        activated: true
      }, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      .then(response => {
        console.log(response.data);
        const actions_id_created = response.data.id;
        console.log("action id created:", actions_id_created);
        this.createReaction(area_id, this.selectedReaction.id, token, actions_id_created);
      })
      .catch(error => {
        console.error('Erreur lors de la création de l\'action :', error);
      })
    },
    createArea() {
      console.log(this.name);
      console.log(this.description);
      console.log("selected actions service id:", this.selectedAction.id);
      console.log("selected reactions service id:", this.selectedReaction.id);
      const token = localStorage.getItem('token');
      if (!token) {
        this.$router.push('/login');
        return;
      }
      this.loading = true;
      axios.post('http://localhost:8000/api/area', {
        name: this.name,
        description: this.description,
        activated: true
      }, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      .then(response => {
        let area_id_created = response.data.id;
        console.log("area id created:", area_id_created);
        this.createAction(area_id_created, this.selectedAction.id, token);
      })
      .catch(error => {
        console.error('Erreur lors de la création de l\'area :', error);
      })
      .finally(() => {
        this.loading = false;
        this.$buefy.notification.open({
          message: 'Area created',
          type: 'is-success',
        });
        this.closeModal();
      });
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
      })
      .catch(error => {
        console.error('Erreur lors de la récupération des services :', error);
      })
      .finally(() => {
      });
    },
    findServiceName(serviceId) {
      const service = this.services.find(s => s.id === serviceId);
      return service ? service.service_name : 'Service non trouvé';
    },
  },
}
</script>