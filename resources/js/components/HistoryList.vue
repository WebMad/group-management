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
        <form ref="fillForm" v-if="history && history_list.length !== 0" @submit.prevent="fillHistory">
            <button @click="deleteSelected()" type="button" class="btn btn-danger">
                <i class="bi-trash"></i> Удалить
            </button>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">ФИО</th>
                    <th scope="col">Присутствует</th>
                    <th scope="col">Уважительная причина</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="student in students">
                    <td>{{ getFio(student) }}</td>
                    <td>
                        <input :name="`students[${student.id}][attend]`" :checked="sessionLogByStudentId(student.id).attend" class="form-control w-25 h-25" type="checkbox">
                    </td>
                    <td>
                        <input :name="`students[${student.id}][valid_reason]`" :checked="sessionLogByStudentId(student.id).valid_reason"  class="form-control w-25 h-25" type="checkbox">
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="form-group">
                <label>Форма заполнена: </label>
                <input class="form-control w-25 h-25" :checked="history.filled" ref="is_filled" type="checkbox">
            </div>

            <div class="form-group">
                <label>Учитывать часы: </label>
                <input class="form-control w-25 h-25" :checked="history.account_hours" ref="account_hours" type="checkbox">
            </div>

            <input class="btn btn-primary" type="submit" value="Сохранить">
        </form>
    </div>
</template>

<script>
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
    },
    methods: {
        getEduHistoryList() {
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
        getFio(student) {
            return `${student.surname} ${student.name} ${student.patronymic}`;
        },
        fillHistory() {
            let data = new FormData();
            data.append('edu_history_id', this.edu_history_id);
            data.append('filled', this.$refs.is_filled.checked ? '1' : '0');
            data.append('account_hours', this.$refs.account_hours.checked ? '1' : '0');
            let inputs = this.$refs.fillForm.getElementsByTagName('input');
            for (let i = 0; i < inputs.length; i++) {
                if (inputs[i].type === 'checkbox') {
                    data.append(inputs[i].name, inputs[i].checked ? '1' : '0');
                }
            }

            axios.post('api/v1/history/fill-history', data);
        },
        sessionLogByStudentId(student_id)
        {
            let res = {
                attend: false,
                valid_reason: false,
            };
            console.log(this.history);
            this.history.session_log.forEach((session_log) => {
                if (student_id === session_log.student_id) {
                    res = session_log;
                }
            });
            return res;
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
