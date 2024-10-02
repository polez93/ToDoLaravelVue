<template>
  <h1 class="text-center w-100">Lista de Tareas</h1>
  <div class="container-list">
    <div class="list-left bg-secondary m-5 rounded">

      <h2>Tareas Pendientes</h2>
      <div v-if="tasks.length === 0">
        <p>No hay tareas disponibles</p>
      </div>

      <ul v-else>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Task</th>
              <th scope="col">created data</th>
              <th scope="col">expired data<button class="ms-2 btn btn-warning" @click="sortByDateP">sort</button></th>
              <th scope="col">option</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="task in tasks" :key="task.id">
              <th scope="row">{{ task.title }}</th>
              <td>{{ task.datecreate }}</td>
              <td>{{ task.dateexpire || 'Sin fecha de expiración' }}</td>
              <td><button class="btn btn-success" @click="toggleComplete(task)">
                  <i class="fa-solid fa-check"></i>{{ task.completed ? 'Pendiente' : '' }}
                </button>
                <button type="button" class="btn btn-primary ms-1" @click="showTaskDetails(task)" data-bs-toggle="modal" data-bs-target="#exampleModal">
                  <i class="fa-solid fa-eye"></i>
                </button>
                <button class="btn btn-warning ms-1" @click="editTask(task)">
                  <i class="fa-solid fa-pen-to-square"></i>
                </button>
                <button class="btn btn-danger ms-1" @click="deleteTask(task.id)">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </ul>
    </div>
    <div class="list-right bg-secondary m-5 rounded">

      <h2>Tareas Completadas</h2>
      <div v-if="completedTasks.length === 0">
        <p>No hay tareas disponibles</p>
      </div>

      <ul v-else>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Task</th>
              <th scope="col">Description</th>
              <th scope="col">Usuario</th>
              <th scope="col">created data</th>
              <th scope="col">expired data <button class="ms-2 btn btn-warning" @click="sortByDateC">sort</button></th>
              <th scope="col">option</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="task in completedTasks" :key="task.id">
              <th scope="row">{{ task.title }}</th>
              <td>{{ task.description }}</td>
              <td>{{ task.user.name }}</td>
              <td>{{ task.datecreate }}</td>
              <td>{{ task.dateexpire || 'Sin fecha de expiración' }}</td>
              <td><button class="btn btn-danger" @click="toggleComplete(task)">
                  <i class="fa-solid fa-arrows-rotate"></i> {{ task.completed ? 'mover a pendiente' : 'Marcar como Completada' }}
                </button></td>
            </tr>
          </tbody>
        </table>
      </ul>
    </div>

  </div>
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">{{ selectedTask.title ? selectedTask.title : '' }}</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p><strong>Descripción:</strong> {{ selectedTask.description ? selectedTask.description : ''  }}</p>
            <p><strong>Categoría:</strong> {{ selectedTask.category ? selectedTask.category : '' }}</p>
            <p><strong>Fecha de Expiración:</strong> {{ selectedTask.dateexpire ? selectedTask.dateexpire : '' }}</p>
            <img v-if="selectedTask.image" :src="selectedTask.image" alt="Task Image" class="img-fluid" />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
        </div>
      </div>
    </div>
  </div>
  <div class="container-list d-flex justify-content-center">
    <div class="bg-secondary m-5 rounded delete-list">

      <h2>Tareas Eliminadas</h2>
      <div v-if="deletedTasks.length === 0">
        <p>No hay tareas disponibles</p>
      </div>

      <ul v-else>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Task</th>
              <th scope="col">Description</th>
              <th scope="col">Usuario</th>
              <th scope="col">created data</th>
              <th scope="col">expired data</th>
              <th scope="col">option</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="task in deletedTasks" :key="task.id">
              <th scope="row">{{ task.title }}</th>
              <td>{{ task.description }}</td>
              <td>{{ task.user.name }}</td>
              <td>{{ task.datecreate }}</td>
              <td>{{ task.dateexpire || 'Sin fecha de expiración' }}</td>
              <td>hasta aqui llego</td>
            </tr>
          </tbody>
        </table>
      </ul>
    </div>
  </div>
 


</template>

<script>
export default {
  name: 'TaskList',
  data() {
    return {
      selectedTask: {
        description: '',
        category: '',
        dateexpire: '',
        image: null
      },
      tasks: [],
      completedTasks: [],
      deletedTasks: [],
      sortDirectionC: 'asc',
      sortDirectionP: 'asc',
    };
  },
  created() {
    this.fetchTasks();
  },
  methods: {
    async fetchTasks() {
      try {
        const token = localStorage.getItem('token');
        const response = await axios.get('/api/tasks', {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
        this.tasks = response.data.tasks;
        this.completedTasks = response.data.completedTasks;
        this.deletedTasks = response.data.deletedTasks;
      } catch (error) {
        console.error('Error al obtener las tareas:', error);
      }
    },
    toggleComplete(task) {
      // Lógica para cambiar el estado de completado de la tarea
      task.completed = !task.completed;
      // Aquí puedes agregar una llamada a la API para actualizar la tarea
      fetch(`/api/tasks/${task.id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        },
        body: JSON.stringify({ completed: task.completed })
      })
        .then(response => response.json())
        .then(data => {
          console.log('Tarea actualizada:', data);
          // Actualizar la lista de tareas después de completar una
          this.fetchTasks();
        })
        .catch(error => {
          console.error('Error al actualizar la tarea:', error);
        });
    },
    sortByDateC() {
      if (this.sortDirectionC === 'asc') {
        this.completedTasks.sort((a, b) => new Date(a.dateexpire) - new Date(b.dateexpire));
        this.sortDirectionC = 'desc';
      } else {
        this.completedTasks.sort((a, b) => new Date(b.datecreate) - new Date(a.dateexpire));
        this.sortDirectionC = 'asc';
      }
    },
    sortByDateP() {
      if (this.sortDirectionP === 'asc') {
        this.tasks.sort((a, b) => new Date(a.dateexpire) - new Date(b.dateexpire));
        this.sortDirectionP = 'desc';
      } else {
        this.tasks.sort((a, b) => new Date(b.datecreate) - new Date(a.dateexpire));
        this.sortDirectionP = 'asc';
      }
    },
    async deleteTask(taskId) {
      try {
        // Enviar solicitud DELETE al backend
        await axios.delete(`/api/tasks/${taskId}`, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
          },
        });

        // Refrescar la lista de tareas después de eliminar
        await this.fetchTasks();

      } catch (error) {
        console.error('Error al eliminar la tarea:', error);
      }
    },
    editTask(task) {
      this.$router.push({ name: 'EditTask', params: { taskId: task.id } });
    },
    // Mostrar detalles de la tarea seleccionada en el modal
    showTaskDetails(task) {
      this.selectedTask = task; // Asigna la tarea seleccionada al modal
    },
    mounted() {
      // Cargar las tareas cuando el componente esté montado
      this.fetchTasks();
    }
  }
};
</script>

<style scoped>
.container-list {
  display: flex;
  justify-content: space-between;
  align-items: stretch;
  flex-wrap: wrap;
}

.container-list div {
  flex: 1;
  min-width: 250px;
  box-sizing: border-box;
  padding: 10px;
  margin: 10px;
  border: 1px solid #ddd;
}

@media (max-width: 780px) {
  .container-list {
    flex-direction: column;
  }
}

.delete-list {
  max-width: 800px;
}

ul {
  list-style: none;
  /* Quitar la viñeta de la lista */
  padding: 0;
  /* Eliminar el padding por defecto */
  margin: 0;
  /* Eliminar el margin por defecto */
  display: flex;
  justify-content: space-around;
}
</style>