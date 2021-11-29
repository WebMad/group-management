<template>
    <div class="history-list">
        <h3>Создать отчет</h3>
        <form @submit.prevent="createReport">
            <button type="submit" class="btn btn-primary">сформировать отчет</button>
        </form>

        <h3>Создание проведенной пары</h3>
        <add-edu-history/>

        <h3>Заполнение посещаемости</h3>
        <div class="form-group">
            <label>Дата: </label>
            <input @change="getEduHistoryList" v-model="date" class="form-control" type="date">
        </div>
        <select @change="getHistoryById" v-if="history_list.length !== 0" v-model="edu_history_id" class="custom-select form-control">
            <option value="">выберите</option>
            <option v-for="edu_history in history_list" :value="edu_history.id">{{ edu_history.subject.name }}</option>
        </select>
        <session-log :key="edu_history_id" v-if="edu_history_id" :eh_id="edu_history_id" />
        <button v-if="edu_history_id" @click="deleteSelected()" type="button" class="mt-3 btn btn-danger">
            <i class="bi-trash"></i> Удалить
        </button>
    </div>
</template>

<script>
import dateFormat, { masks } from "dateformat";

export default {
    name: "HistoryList",
    data() {
        return {
            date: null,
            history_list: [],
            history: null,
            students: [],
            edu_history_id: null,
        }
    },
    mounted() {
        this.date = dateFormat(new Date(), "yyyy-mm-dd");
        this.getEduHistoryList();
    },
    methods: {
        getEduHistoryList() {
            this.edu_history_id = null;
            axios.post('api/v1/history/show-by-date', {
                date: this.date
            }).then(response => {
                this.history_list = response.data;
            });
        },

        getHistoryById() {
            axios.post('api/v1/history/show', {
                edu_history_id: this.edu_history_id
            }).then(response => {
                this.history = response.data;
                axios.get('api/v1/student').then(response => {
                    this.students = response.data;
                });
            });
        },
        deleteSelected()
        {
            axios.post('api/v1/history/delete', {
                id: this.edu_history_id
            }).then(() => {
                this.history_list = [];
                this.date = null;
                this.history = null;
            });
        },
        createReport()
        {
            window.open('/sheet', '_blank');
        }
    }
}
</script>

<style scoped>

</style>
