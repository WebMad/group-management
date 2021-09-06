<template>
    <div class="teacher-list">
        <button type="button" @click="is_add = !is_add" class="btn btn-primary">
            <i class="bi-plus-lg"></i>
        </button>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Название</th>
                <th scope="col">Преподаватель</th>
                <th scope="col">Действие</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="is_add">
                <th scope="row"></th>
                <td><input type="text" v-model="add.name" class="form-control"></td>
                <td>
                    <select v-model="add.teacher_id" class="custom-select form-control">
                        <option value="">выберите</option>
                        <option v-for="teacher in teachers" :value="teacher.id">{{ getFioByTeacher(teacher) }}</option>
                    </select>
                </td>
                <td class="no-wrap">
                    <button @click="addSubject()" type="button" class="btn btn-primary">
                        <i class="bi-check"></i>
                    </button>
                    <button @click="cancelAddSubject()" type="button" class="btn btn-danger">
                        <i class="bi-x"></i>
                    </button>
                </td>
            </tr>
            <tr v-for="subject in subjects">
                <th scope="row">{{ subject.id }}</th>

                <td v-if="+subject.id !== +edit_id">{{ subject.name }}</td>
                <td v-else>
                    <input type="text" class="form-control" v-model="edit.name">
                </td>

                <td v-if="+subject.id !== +edit_id">
                    {{ subject.teacher_id ? getFioByTeacher(getTeacherBySubject(subject)) : '' }}
                </td>
                <td v-else>
                    <select v-model="edit.teacher_id" class="custom-select form-control">
                        <option value="">выберите</option>
                        <option v-for="teacher in teachers" :value="teacher.id">{{ getFioByTeacher(teacher) }}</option>
                    </select>
                </td>

                <td v-if="+subject.id !== +edit_id">
                    <button @click="editSubject(subject.id)" type="button" class="btn btn-primary">
                        <i class="bi-pencil"></i>
                    </button>
                    <button @click="removeSubject(subject.id)" type="button" class="btn btn-danger">
                        <i class="bi-trash"></i>
                    </button>
                </td>
                <td class="no-wrap" v-else>
                    <button @click="doneEditSubject(subject.id)" type="button" class="btn btn-primary">
                        <i class="bi-check"></i>
                    </button>
                    <button @click="cancelEditSubject()" type="button" class="btn btn-danger">
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
    name: "SubjectList",
    data() {
        return {
            edit_id: null,
            is_add: false,
            teachers: [],
            subjects: [],
            edit: {
                name: '',
                teacher_id: null,
            },
            add: {
                name: '',
                teacher_id: null,
            },
        }
    },
    mounted() {
        this.getTeachers();
        this.getSubjects();
    },
    methods: {
        getSubjects() {
            axios.get('api/v1/subject').then(response => {
                this.subjects = response.data
            });
        },
        getTeachers() {
            axios.get('api/v1/teacher').then(response => {
                this.teachers = response.data
            });
        },
        getTeacherBySubject(subject) {
            return this.teachers.find(function (teacher) {
                if (+teacher.id === +subject.teacher_id) {
                    return true;
                }
            });
        },
        getFioByTeacher(teacher) {
            return `${teacher.surname} ${teacher.name} ${teacher.patronymic}`;
        },
        removeSubject(id) {
            axios.delete(`api/v1/subject/${id}`).then(response => {
                this.getSubjects();
            });
        },
        editSubject(id) {
            let subject = this.subjects.find(function (subject) {
                if (+subject.id === +id) {
                    return true;
                }
            });
            this.edit.name = subject.name;
            this.edit.teacher_id = subject.teacher_id;

            this.edit_id = id;
        },
        doneEditSubject(id) {
            this.edit_id = null;
            axios.put(`api/v1/subject/${id}`, {
                id: id,
                name: this.edit.name,
                teacher_id: this.edit.teacher_id,
            }).then(response => {
                this.getSubjects();
            });
        },
        cancelEditSubject() {
            this.edit.name = '';
            this.edit.teacher_id = '';
            this.edit_id = null;
        },
        cancelAddSubject() {
            this.add.name = '';
            this.add.teacher_id = '';
            this.is_add = false;
        },
        addSubject() {
            axios.post('/api/v1/subject', {
                name: this.add.name,
                teacher_id: this.add.teacher_id,
            }).then(() => {
                this.cancelAddSubject()
                this.is_add = false;
                this.getSubjects();
            });
        }
    }
}
</script>

<style scoped>

</style>
