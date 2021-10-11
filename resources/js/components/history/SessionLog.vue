<template>
    <form ref="fillForm" v-if="history" @submit.prevent="fillHistory">

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
                    <input
                        :name="`students[${student.id}][attend]`"
                        :checked="sessionLogByStudentId(student.id).attend"
                        class="form-control"
                        type="checkbox"
                    >
                </td>
                <td>
                    <input
                        :name="`students[${student.id}][valid_reason]`"
                        :checked="sessionLogByStudentId(student.id).valid_reason"
                        class="form-control"
                        type="checkbox"
                    >
                </td>
            </tr>
            </tbody>
        </table>
        <div class="form-inline">
            <div class="form-check">
                <label class="form-check-label">Форма заполнена: </label>
                <input class="form-check-input ml-1" :checked="history.filled" ref="is_filled" type="checkbox">
            </div>
        </div>
        <div class="form-inline">
            <div class="form-check">
                <label class="form-check-label">Учитывать часы: </label>
                <input class="form-check-input ml-1" :checked="history.account_hours" ref="account_hours" type="checkbox">
            </div>
        </div>

        <input class="btn btn-primary" type="submit" value="Сохранить">
    </form>
</template>

<script>
export default {
    name: "SessionLog",
    props: ['eh_id'],
    data() {
        return {
            students: [],
            history: [],
        }
    },
    mounted() {
        this.getHistoryById();
    },
    methods: {
        getHistoryById() {
            axios.post('api/v1/history/show', {
                edu_history_id: this.eh_id
            }).then(response => {
                this.history = response.data;
                axios.get('api/v1/student').then(response => {
                    this.students = response.data;
                });
            });
        },
        sessionLogByStudentId(student_id)
        {
            let res = {
                attend: false,
                valid_reason: false,
            };

            this.history.session_log.forEach((session_log) => {
                if (student_id === session_log.student_id) {
                    res = session_log;
                }
            });
            return res;
        },
        fillHistory() {
            let data = new FormData();
            data.append('edu_history_id', this.eh_id);
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
        getFio(student) {
            return `${student.surname} ${student.name} ${student.patronymic}`;
        },
    }
}
</script>

<style scoped>
input[type="checkbox"] {
    width: 15px;
    height: 15px;
}
</style>
