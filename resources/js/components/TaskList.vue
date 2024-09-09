<template>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Task List</h1>
        <div class="form-group">
            <label class="mb-1 mr-2" for="selectFilter">Filter</label>
            <select class="custom-select my-1 mr-sm-2" v-model="selectedFilter" id="selectFilter">
                <option v-for="option in optionFilters" :value="option.value">
                    {{ option.text }}
                </option>
            </select>
        </div>
        <div v-show="loading" class="spinner-border spinner-border-sm" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <ul class="list-group mb-4" v-show="!loading">
            <li v-for="task in tasks" :key="task.id" class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">{{ task.title }}</h5>
                    <p class="mb-1">{{ task.description }}</p>
                    <small class="text-muted">Assigned to:     {{ task.user ? task.user.name : 'Unknown' }} - {{ task.user ? task.user.email : 'No email available' }}</small>
                </div>
                <div>
                    <button :disabled="!!task.completed" class="btn btn-success btn-sm mr-2" @click="completeTask(task.id)">Complete</button>
                    <button class="btn btn-danger btn-sm" @click="deleteTask(task.id)">Delete</button>
                </div>
            </li>
        </ul>

        <!-- Controles de paginación -->
        <div v-if="pagination.total > 0">
            <nav>
                <ul class="pagination">
                    <li class="page-item" :class="{ disabled: pagination.currentPage === 1 }">
                        <a class="page-link" @click.prevent="changePage(pagination.currentPage - 1)" href="#">Previous</a>
                    </li>
                    <li v-for="page in pagination.lastPage" :key="page" class="page-item" :class="{ active: page === pagination.currentPage }">
                        <a class="page-link" @click.prevent="changePage(page)" href="#">{{ page }}</a>
                    </li>
                    <li class="page-item" :class="{ disabled: pagination.currentPage === pagination.lastPage }">
                        <a class="page-link" @click.prevent="changePage(pagination.currentPage + 1)" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>

        <form @submit.prevent="addTask" class="card card-body">
            <div class="form-group">
                <input v-model="newTask.title" class="form-control" placeholder="Task Title" required>
            </div>
            <div class="form-group">
                <input v-model="newTask.description" class="form-control" placeholder="Task Description" required>
            </div>
            <div class="form-group">
                <input v-model="newTask.user" class="form-control" placeholder="Assigned User" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Add Task</button>
        </form>
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex';

export default {
    data() {
        return {
            newTask: {
                title: '',
                description: '',
                user: ''
            },
            selectedFilter: null,
            loading: false,
            localPagination: { 
                currentPage: 1,
                lastPage: 1,
                total: 0
            },
            optionFilters: [
                { value: null, text: "All"},
                { value: "pending", text: "Pending"},
                { value: "completed", text: "Completed"}
            ]
        };
    },
    computed: {
        ...mapState(['tasks', 'pagination']), 
    },
    methods: {
        ...mapActions(['fetchTasks', 'addTask', 'completeTask', 'deleteTask']),
        addTask() {
            if (!this.newTask.title || !this.newTask.description || !this.newTask.user) {
                alert('Both title and description are required');
                return;
            }
            this.$store.dispatch('addTask', {task: this.newTask, filter: this.selectedFilter}).then(() => {
                this.newTask.title = '';
                this.newTask.description = '';
                this.newTask.user = '';
            }).catch(error => {
                console.error('Error adding task:', error);
            });
        },
        completeTask(taskId) {
            this.$store.dispatch('completeTask', {taskId: taskId, filter: this.selectedFilter}).then((response)=>{
                console.log('Task completed:', response);
            }).catch(error => {
                console.error('Error completing task:', error);
            });
        },
        deleteTask(taskId) {
            this.$store.dispatch('deleteTask', taskId).catch(error => {
                console.error('Error deleting task:', error);
            });
        },
        refreshTasks(page = 1) {
            this.loading = true;
            this.$store.dispatch('fetchTasks', { params: { filter: this.selectedFilter, page } })
                .then(response => {
                    this.pagination.currentPage = response.pagination.currentPage;
                    this.pagination.lastPage = response.pagination.lastPage;
                    this.pagination.total = response.pagination.total;
                })
                .catch(error => {
                    console.error("Error refreshing tasks:", error);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        changePage(page) {
            if (page < 1 || page > this.pagination.lastPage) return; 
            this.refreshTasks(page);
        }
    },
    watch: {
        selectedFilter() {
            this.refreshTasks();
        }
    },
    mounted() {
        this.refreshTasks();
    }
};
</script>
