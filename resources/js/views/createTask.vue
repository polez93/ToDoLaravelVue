<template>
    <div class="row justify-content-center">
        <div class="card p-4 w-25">
            <h1>{{ isEdit ? 'Editar Tarea' : 'Crear Tarea' }}</h1>
            <form @submit.prevent="saveTask">
                <div>
                    <label for="title">Título:</label>
                    <input class="form-control" v-model="title" type="text" id="title" placeholder="Título" required />
                </div>

                <div>
                    <label for="description">Descripción:</label>
                    <textarea class="form-control" v-model="description" id="description"
                        placeholder="Descripción"></textarea>
                </div>

                <div>
                    <label for="category">Categoría:</label>
                    <select class="form-control" v-model="category" id="category" required>
                        <option value="urgente">Urgente</option>
                        <option value="importante">Importante</option>
                        <option value="necesario">Necesario</option>
                    </select>
                </div>

                <div>
                    <label for="dateexpire">Fecha de Expiración:</label>
                    <input class="form-control" v-model="dateexpire" type="date" id="dateexpire" required />
                </div>

                <div class="mt-3">
                    <!-- Input para la carga de imagen -->
                    <input class="form-control" type="file" id='image' @change="onFileChange" accept="image/*" />
                    <p v-if="imagePreview">Vista previa:</p>
                    <img v-if="imagePreview" :src="imagePreview" alt="Vista previa" width="100" />
                </div>

                <button class="btn btn-success mt-3" type="submit">{{ isEdit ? 'Actualizar' : 'Crear' }}</button>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        taskId: {
            type: String,
            default: null,
        },
    },
    data() {
        return {
            title: '',
            description: '',
            category: 'importante',
            dateexpire: '',
            image: null,
            imagePreview: null,
            isEdit: false,
        };
    },
    async created() {
        console.log("Task ID:", this.taskId);
        if (this.taskId) {
            this.isEdit = true;
            await this.fetchTask();
        }
    },
    methods: {
        onFileChange(event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                this.image = file;
                const reader = new FileReader();
                reader.onload = e => {
                    this.imagePreview = e.target.result; // Muestra la vista previa de la imagen
                };
                reader.readAsDataURL(file);
            } else {
                alert('Por favor, selecciona una imagen válida.');
            }
        },
        async fetchTask() {
            const token = localStorage.getItem('token');
            try {
                const token = localStorage.getItem('auth_token');
                const response = await axios.get(`/api/tasks/${this.taskId}`, {
                    headers: {
                        'Authorization': `Bearer ${token}` // Agrega el token aquí
                    }
                });
                const task = response.data;
                this.title = task.title;
                this.description = task.description;
                this.category = task.category;
                this.dateexpire = task.dateexpire;
            } catch (error) {
                console.error('Error al obtener la tarea:', error);
            }
        },
        async saveTask() {
            try {
                const token = localStorage.getItem('token');
                const formData = new FormData();
                if (this.isEdit) {
                    if (this.image) {
                        formData.append('image', this.image); // Si hay una nueva imagen, la añadimos al FormData
                    }
                    // Agrega los demás campos siempre
                    formData.append('title', this.title);
                    formData.append('description', this.description);
                    formData.append('category', this.category);
                    formData.append('dateexpire', this.dateexpire);
                    formData.append('_method', 'PUT');

                    await axios.post(`/api/tasks/${this.taskId}`, formData, {
                        headers: {
                            'Authorization': `Bearer ${token}`,     // Agrega el token aquí
                            'Content-Type': 'multipart/form-data'  
                        }
                    });
                } else {
                    formData.append('image', this.image);
                    formData.append('title', this.title);
                    formData.append('description', this.description);
                    formData.append('category', this.category);
                    formData.append('dateexpire', this.dateexpire);

                    await axios.post('/api/tasks', formData, {
                        headers: {
                            'Authorization': `Bearer ${token}` // Agrega el token aquí
                        }
                    });
                }
                this.$router.push('/tasks');
            } catch (error) {
                console.error('Error guardando la tarea:', error);
            }
        },
    }
};
</script>