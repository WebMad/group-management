<template>
  <div class="card">
    <div v-if="unit" class="card-body p-2">
      <h5 class="card-title">
        {{ unit.subject.name }}
        <a @click="deleteUnit(unit.id)" href="#"><i class="bi-trash text-danger"></i></a>
      </h5>
      <h6 v-if="unit.start_week || unit.end_week" class="card-subtitle mb-2 text-muted">
        <span v-if="unit.start_week">c {{ unit.start_week }}</span>
        <span v-if="unit.end_week">по {{ unit.end_week }}</span> нед.
      </h6>
      <p class="card-text">{{ unit.address }}</p>
    </div>
    <div v-else-if="is_add" class="card-body text-center p-2">
      <div class="form-group">
        <label>Предмет: </label>
        <select v-model="add.subject_id" class="custom-select form-control">
          <option value="0">выберите</option>
          <option v-for="subject in subjects" :value="subject.id">{{ subject.name }}</option>
        </select>
      </div>
      <div class="form-group">
        <label>Период недель: </label>
        <div class="row">
          <div class="col-md-6 col-sm-12 input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">с</span>
            </div>
            <input type="text" v-model="add.start_week" class="form-control">
          </div>
          <div class="col-md-6 col-sm-12 input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">по</span>
            </div>
            <input type="text" v-model="add.end_week" class="form-control">
          </div>
        </div>
      </div>
      <div class="form-group">
        <label>Адрес: </label>
        <input type="text" v-model="add.address" class="form-control">
      </div>
      <div class="form-group">
        <label>Тип повтора: </label>
        <select v-model="add.week_type" class="custom-select form-control">
          <option value="0">Каждую неделю</option>
          <option value="1">Каждую нечетную</option>
          <option value="2">Каждую четную</option>
        </select>
      </div>
      <div class="form-group">
        <button @click="addUnit()" type="button" class="btn btn-primary">
          <i class="bi-check"></i>
        </button>
        <button @click="cancelAddUnit()" type="button" class="btn btn-danger">
          <i class="bi-x"></i>
        </button>
      </div>
    </div>
    <div v-else class="card-body text-center p-2">
      <button type="button" @click="is_add = !is_add" class="btn btn-light">
        <i class="bi-plus-lg"></i>
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: "ScheduleUnit",
  props: [
    'unit',
    'subjects',
    'scheme_id',
    'day_of_week',
  ],
  data() {
    return {
      is_add: false,
      add: {
        subject_id: 0,
        start_week: null,
        end_week: null,
        address: null,
        week_type: 0,
      }
    }
  },
  mounted() {

  },
  methods: {
    cancelAddUnit() {
      this.add.subject_id = 0;
      this.is_add = false;
    },
    addUnit() {
      axios.post('api/v1/schedule', {
        subject_id: this.add.subject_id,
        scheme_id: this.scheme_id,
        week_type: this.add.week_type,
        day_of_week: this.day_of_week,
        start_week: this.add.start_week,
        end_week: this.add.end_week,
        address: this.add.address,
      }).then(response => {
        this.cancelAddUnit();
        this.$emit('added');
      });
    },
    deleteUnit(id) {
      axios.delete(`api/v1/schedule/${id}`).then(() => {
        this.$emit('added');
      });
    }
  }
}
</script>

<style scoped>

</style>
