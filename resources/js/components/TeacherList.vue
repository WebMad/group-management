<template>
    <div class="teacher-list">
        <button type="button" @click="is_add = !is_add" class="btn btn-primary">
            <i class="bi-plus-lg"></i>
        </button>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Фамилия</th>
                <th scope="col">Имя</th>
                <th scope="col">Отчество</th>
                <th scope="col">Почта</th>
                <th scope="col">Телефон</th>
                <th scope="col">Действия</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="is_add">
                <th scope="row"></th>
                <td><input type="text" v-model="add.surname" class="form-control"></td>
                <td><input type="text" v-model="add.name" class="form-control"></td>
                <td><input type="text" v-model="add.patronymic" class="form-control"></td>
                <td><input type="text" v-model="add.email" class="form-control"></td>
                <td><input type="text" v-model="add.phone" class="form-control"></td>
                <td class="no-wrap">
                    <button @click="addTeacher()" type="button" class="btn btn-primary">
                        <i class="bi-check"></i>
                    </button>
                    <button @click="cancelAddTeacher()" type="button" class="btn btn-danger">
                        <i class="bi-x"></i>
                    </button>
                </td>
            </tr>
            <tr v-for="teacher in teachers">
                <th scope="row">{{ teacher.id }}</th>

                <td v-if="+teacher.id !== +edit_id">{{ teacher.surname }}</td>
                <td v-else>
                    <input type="text" class="form-control" v-model="edit.surname">
                </td>

                <td v-if="+teacher.id !== +edit_id">{{ teacher.name }}</td>
                <td v-else>
                    <input type="text" class="form-control" v-model="edit.name">
                </td>

                <td v-if="+teacher.id !== +edit_id">{{ teacher.patronymic }}</td>
                <td v-else>
                    <input type="text" class="form-control" v-model="edit.patronymic">
                </td>

                <td v-if="+teacher.id !== +edit_id">{{ teacher.email || 'Нет данных' }}</td>
                <td v-else>
                    <input type="text" class="form-control" v-model="edit.email">
                </td>

                <td v-if="+teacher.id !== +edit_id">{{ teacher.phone }}</td>
                <td v-else>
                    <input type="text" class="form-control" v-model="edit.phone">
                </td>

                <td v-if="+teacher.id !== +edit_id">
                    <button @click="editTeacher(teacher.id)" type="button" class="btn btn-primary">
                        <i class="bi-pencil"></i>
                    </button>
                    <button @click="removeTeacher(teacher.id)" type="button" class="btn btn-danger">
                        <i class="bi-trash"></i>
                    </button>
                </td>
                <td class="no-wrap" v-else>
                    <button @click="doneEditTeacher(teacher.id)" type="button" class="btn btn-primary">
                        <i class="bi-check"></i>
                    </button>
                    <button @click="cancelEditTeacher()" type="button" class="btn btn-danger">
                        <i class="bi-x"></i>
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "TeacherList",
    data() {
        return {
            edit_id: null,
            is_add: false,
            teachers: [],
            edit: {
                surname: '',
                name: '',
                patronymic: '',
                phone: '',
                email: '',
            },
            add: {
                surname: '',
                name: '',
                patronymic: '',
                phone: '',
                email: '',
            },
        }
    },
    mounted() {
        this.getTeachers();
    },
    methods: {
        getTeachers() {
            axios.get('api/v1/teacher').then(response => {
                this.teachers = response.data
            });
        },
        removeTeacher(id) {
            axios.delete(`api/v1/teacher/${id}`).then(response => {
                this.getTeachers();
            });
        },
        editTeacher(id) {
            let teacher = this.teachers.find(function (teacher) {
                if (+teacher.id === +id) {
                    return true;
                }
            });
            this.edit.surname = teacher.surname;
            this.edit.name = teacher.name;
            this.edit.patronymic = teacher.patronymic;
            this.edit.phone = teacher.phone;
            this.edit.email = teacher.email;

            this.edit_id = id;
        },
        doneEditTeacher(id) {
            this.edit_id = null;
            axios.put(`api/v1/teacher/${id}`, {
                id: id,
                surname: this.edit.surname,
                name: this.edit.name,
                patronymic: this.edit.patronymic,
                phone: this.edit.phone,
                email: this.edit.email,
            }).then(response => {
                this.getTeachers();
            });
        },
        cancelEditTeacher() {
            this.edit.surname = '';
            this.edit.name = '';
            this.edit.patronymic = '';
            this.edit.phone = '';
            this.edit.email = '';
            this.edit_id = null;
        },
        cancelAddTeacher() {
            this.add.surname = '';
            this.add.name = '';
            this.add.patronymic = '';
            this.add.phone = '';
            this.add.email = '';
            this.is_add = false;
        },
        addTeacher() {
            axios.post('/api/v1/teacher', {
                surname: this.add.surname,
                name: this.add.name,
                patronymic: this.add.patronymic,
                phone: this.add.phone,
                email: this.add.email,
            }).then(() => {
                this.cancelAddTeacher()
                this.is_add = false;
                this.getTeachers();
            });
        }
    }
}
</script>

<style scoped>

</style>
