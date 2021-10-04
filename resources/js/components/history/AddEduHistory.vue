<template>
    <div class="add-edu-history">
        <form @submit.prevent="createEduHistory">
            <div class="input-group input-group-sm mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Дата</span>
                </div>
                <input v-model="date" type="date" class="form-control"/>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <select v-model="subject_id" class="custom-select form-control">
                        <option value="">выберите предмет</option>
                        <option :value="subject.id" v-for="subject in subjects">{{ subject.name }}</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <select v-model="scheme_id" class="custom-select form-control">
                        <option value="">выберите время</option>
                        <option :value="unit.id" v-for="unit in scheme">{{ unit.start_time.substr(0, 5) }}-{{ unit.end_time.substr(0, 5) }}</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">добавить</button>
        </form>
    </div>
</template>

<script>
import {TheMask} from 'vue-the-mask'

export default {
    name: "AddEduHistory",
    components: {TheMask},
    data() {
        return {
            subjects: [],
            scheme: [],
            scheme_id: null,
            subject_id: null,
            date: "",
        }
    },
    mounted() {
        this.getSubjects();
        this.getScheme();
    },
    methods: {
        getSubjects() {
            axios.get('api/v1/subject').then(response => {
                this.subjects = response.data
            });
        },
        getScheme() {
            axios.get('api/v1/schedule-scheme').then(response => {
                this.scheme = response.data;
            });
        },
        createEduHistory() {
            axios.post('api/v1/history/create', {
                scheme_id: this.scheme_id,
                subject_id: this.subject_id,
                date: this.date
            }).then(() => {
                this.scheme_id = null;
                this.subject_id = null;
                this.date = "";
            });
        }
    }
}
</script>

<style scoped>

</style>
