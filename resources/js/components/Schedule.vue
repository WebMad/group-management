<template>
    <div class="teacher-list">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">Время</th>
                <th scope="col">Понедельник</th>
                <th scope="col">Вторник</th>
                <th scope="col">Среда</th>
                <th scope="col">Четверг</th>
                <th scope="col">Пятница</th>
                <th scope="col">Суббота</th>
                <th scope="col">Воскресенье</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="unit in scheme">
                <td>{{ unit.start_time.slice(0, -3) }} - {{ unit.end_time.slice(0, -3) }}</td>

                <td v-for="index in [1, 2, 3, 4, 5, 6, 0]">
                    <schedule-unit
                        :unit="getUnitBySchemeIdDayOfWeekWeekType(unit.id, index, 1)
                        || getUnitBySchemeIdDayOfWeekWeekType(unit.id, index, 0)"
                        :subjects="subjects"
                        :scheme_id="unit.id"
                        :day_of_week="index"
                        @added="getSchedule()"
                    />
                    <schedule-unit
                        v-if="getUnitBySchemeIdDayOfWeekWeekType(unit.id, index, 1)
                        || getUnitBySchemeIdDayOfWeekWeekType(unit.id, index, 2)
                        && !getUnitBySchemeIdDayOfWeekWeekType(unit.id, index, 0)"
                        :unit="getUnitBySchemeIdDayOfWeekWeekType(unit.id, index, 2)"
                        :subjects="subjects"
                        :scheme_id="unit.id"
                        :day_of_week="index"
                        @added="getSchedule()"
                    />
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import ScheduleUnit from "./schedule/ScheduleUnit";
export default {
    name: "schedule",
    components: {ScheduleUnit},
    data() {
        return {
            scheme: [],
            schedule: [],
            subjects: [],
        }
    },
    mounted() {
        this.getScheme();
        this.getSchedule();
        this.getSubjects();
    },
    methods: {
        getSubjects() {
            axios.get('api/v1/subject').then(response => {
                this.subjects = response.data;
            });
        },
        getScheme() {
            axios.get('api/v1/schedule-scheme').then(response => {
                this.scheme = response.data;
            });
        },
        getSchedule() {
            axios.get('api/v1/schedule').then(response => {
                this.schedule = response.data;
            });
        },
        getUnitBySchemeIdDayOfWeekWeekType(scheme_id, day_of_week, week_type)
        {
            return this.schedule.find(item => {
                return +item.scheme_id === +scheme_id
                    && +day_of_week === item.day_of_week
                    && +item.week_type === +week_type
            });
        }
    }
}
</script>

<style scoped>

</style>
