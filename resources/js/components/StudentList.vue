<template>
    <div class="student-list">
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
                <th scope="col">Код</th>
                <th scope="col">Почта</th>
                <th scope="col">Телефон</th>
                <th scope="col">ID ВК</th>
                <th scope="col">Действия</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="is_add">
                <th scope="row"></th>
                <td><input type="text" v-model="add.surname" class="form-control"></td>
                <td><input type="text" v-model="add.name" class="form-control"></td>
                <td><input type="text" v-model="add.patronymic" class="form-control"></td>
                <td><input type="text" v-model="add.code" class="form-control"></td>
                <td><input type="text" v-model="add.email" class="form-control"></td>
                <td><input type="text" v-model="add.phone" class="form-control"></td>
                <td><input type="text" v-model="add.vk_id" class="form-control"></td>
                <td class="no-wrap">
                    <button @click="addStudent()" type="button" class="btn btn-primary">
                        <i class="bi-check"></i>
                    </button>
                    <button @click="cancelAddStudent()" type="button" class="btn btn-danger">
                        <i class="bi-x"></i>
                    </button>
                </td>
            </tr>
            <tr v-for="student in students">
                <th scope="row">{{ student.id }}</th>

                <td v-if="+student.id !== +edit_id">{{ student.surname }}</td>
                <td v-else>
                    <input type="text" class="form-control" v-model="edit.surname">
                </td>

                <td v-if="+student.id !== +edit_id">{{ student.name }}</td>
                <td v-else>
                    <input type="text" class="form-control" v-model="edit.name">
                </td>

                <td v-if="+student.id !== +edit_id">{{ student.patronymic }}</td>
                <td v-else>
                    <input type="text" class="form-control" v-model="edit.patronymic">
                </td>

                <td v-if="+student.id !== +edit_id">{{ student.code }}</td>
                <td v-else>
                    <input type="text" class="form-control" v-model="edit.code">
                </td>

                <td v-if="+student.id !== +edit_id">{{ student.email || 'Нет данных' }}</td>
                <td v-else>
                    <input type="text" class="form-control" v-model="edit.email">
                </td>

                <td v-if="+student.id !== +edit_id">{{ student.phone }}</td>
                <td v-else>
                    <input type="text" class="form-control" v-model="edit.phone">
                </td>

                <td v-if="+student.id !== +edit_id">{{ student.vk_id }}</td>
                <td v-else>
                    <input type="text" class="form-control" v-model="edit.vk_id">
                </td>

                <td v-if="+student.id !== +edit_id">
                    <button @click="editStudent(student.id)" type="button" class="btn btn-primary">
                        <i class="bi-pencil"></i>
                    </button>
                    <button @click="removeStudent(student.id)" type="button" class="btn btn-danger">
                        <i class="bi-trash"></i>
                    </button>
                </td>
                <td class="no-wrap" v-else>
                    <button @click="doneEditStudent(student.id)" type="button" class="btn btn-primary">
                        <i class="bi-check"></i>
                    </button>
                    <button @click="cancelEditStudent()" type="button" class="btn btn-danger">
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
            students: [],
            edit: {
                surname: '',
                name: '',
                patronymic: '',
                phone: '',
                email: '',
                code: '',
                vk_id: '',
            },
            add: {
                surname: '',
                name: '',
                patronymic: '',
                phone: '',
                email: '',
                code: '',
                vk_id: '',
            },
        }
    },
    mounted() {
        this.getStudents();
    },
    methods: {
        getStudents() {
            axios.get('api/v1/student').then(response => {
                this.students = response.data
            });
        },
        removeStudent(id) {
            axios.delete(`api/v1/student/${id}`).then(response => {
                this.getStudents();
            });
        },
        editStudent(id) {
            let student = this.students.find(function (teacher) {
                if (+teacher.id === +id) {
                    return true;
                }
            });
            this.edit.surname = student.surname;
            this.edit.name = student.name;
            this.edit.patronymic = student.patronymic;
            this.edit.phone = student.phone;
            this.edit.email = student.email;
            this.edit.code = student.code;
            this.edit.vk_id = student.vk_id;

            this.edit_id = id;
        },
        doneEditStudent(id) {
            this.edit_id = null;
            axios.put(`api/v1/student/${id}`, {
                id: id,
                surname: this.edit.surname,
                name: this.edit.name,
                patronymic: this.edit.patronymic,
                phone: this.edit.phone,
                email: this.edit.email,
                code: this.edit.code,
                vk_id: this.edit.vk_id,
            }).then(response => {
                this.getStudents();
            });
        },
        cancelEditStudent() {
            this.edit.surname = '';
            this.edit.name = '';
            this.edit.patronymic = '';
            this.edit.phone = '';
            this.edit.email = '';
            this.edit.code = '';
            this.edit.vk_id = '';
            this.edit_id = null;
        },
        cancelAddStudent() {
            this.add.surname = '';
            this.add.name = '';
            this.add.patronymic = '';
            this.add.phone = '';
            this.add.email = '';
            this.add.code = '';
            this.add.vk_id = '';
            this.is_add = false;
        },
        addStudent() {
            axios.post('/api/v1/student', {
                surname: this.add.surname,
                name: this.add.name,
                patronymic: this.add.patronymic,
                phone: this.add.phone,
                email: this.add.email,
                code: this.add.code,
                vk_id: this.add.vk_id,
            }).then(() => {
                this.cancelAddStudent()
                this.is_add = false;
                this.getStudents();
            });
        }
    }
}
</script>

<style scoped>

</style>
