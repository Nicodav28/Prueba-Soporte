import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        tasks: [],
        pagination: {
            currentPage: 1,
            lastPage: 1,
            perPage: 10,
            total: 0
        }
    },
    mutations: {
        FETCH_TASKS(state, tasks) {
            state.tasks = tasks;
        },
        SET_PAGINATION(state, pagination) {
            state.pagination = pagination;
        },
        ADD_TASK(state, { task, filter }) {
            if (filter !== "completed") {
                state.tasks.push(task);
            }

        },
        COMPLETE_TASK(state, { completedTask, filter }) {
            if (filter !== "pending") {
                const index = state.tasks.findIndex(t => t.id === completedTask.id);
                if (index !== -1) {
                    Vue.set(state.tasks, index, completedTask);
                }
            } else {
                state.tasks = state.tasks.filter(t => t.id !== completedTask.id);
            }
        },
        UPDATE_TASK(state, updatedTask) {
            const index = state.tasks.findIndex(t => t.id === updatedTask.id);
            if (index !== -1) {
                Vue.set(state.tasks, index, updatedTask);
            }
            window.location.reload()
        },
        DELETE_TASK(state, taskId) {
            state.tasks = state.tasks.filter(t => t.id !== taskId);
        }
    },
    actions: {
        fetchTasks({ commit }, payload) {
            axios.get(`/tasks`, payload)
                .then(response => {
                    if (response.data.resultCode === 'SUCCESS') {
                        // Accedemos a las tareas y a los metadatos de la paginación
                        const tasksData = response.data.result.data; // Aquí están las tareas
                        const pagination = {
                            currentPage: response.data.result.current_page,
                            lastPage: response.data.result.last_page,
                            perPage: response.data.result.per_page,
                            total: response.data.result.total
                        } || {};

                        commit('FETCH_TASKS', tasksData);
                        commit('SET_PAGINATION', pagination);
                    } else {
                        console.error(response.data.resultMessage);
                    }
                })
                .catch(error => {
                    console.error("Error getting tasks:", error);
                });
        },
        addTask({ commit }, { task, filter }) {
            axios.post('/tasks', task)
                .then(response => {
                    if (response.data.resultCode === 'SUCCESS') {
                        task = response.data.result;
                        commit('ADD_TASK', { task, filter });
                    } else {
                        console.error(response.data.resultMessage);
                    }
                })
                .catch(error => {
                    console.error("Error adding task:", error);
                });
        },
        completeTask({ commit }, { taskId, filter }) {
            axios.post(`/tasks-complete/${taskId}`)
                .then(response => {
                    if (response.data.resultCode === 'SUCCESS') {
                        console.log(response.data);
                        commit('COMPLETE_TASK', { completedTask: response.data.result, filter: filter });
                    } else {
                        console.error(response.data.resultMessage);
                    }
                })
                .catch(error => {
                    console.error("Error completing task:", error);
                });
        },
        updateTask({ commit }, task) {
            axios.put(`/tasks/${task.id}`, task)
                .then(response => {
                    commit('UPDATE_TASK', response.data);
                })
                .catch(error => {
                    console.error("Error updating task:", error);
                });
        },
        deleteTask({ commit }, taskId) {
            axios.delete(`/tasks/${taskId}`)
                .then(response => {
                    if (response.data.resultCode === 'SUCCESS') {
                        commit('DELETE_TASK', taskId);
                    } else {
                        console.error(response.data.resultMessage);
                    }
                })
                .catch(error => {
                    console.error("Error deleting task:", error);
                });
        }
    },
    getters: {
        tasks: state => state.tasks
    }
});
