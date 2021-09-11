<template>
    <div class="input-group input-group-sm mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">{{ system_setting.name }}</span>
        </div>
        <input type="text" v-model="value" class="form-control">
        <div class="input-group-append">
            <button @click="updateMe" type="button" class="btn btn-primary">
                <i class="bi-check"></i>
            </button>
            <button @click="deleteMe" type="button" class="btn btn-danger">
                <i class="bi-trash"></i>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "SystemSetting",
    props: [
        'system_setting'
    ],
    data() {
        return {
            value: ''
        }
    },
    mounted() {
        this.value = this.system_setting.value
    },
    methods: {
        deleteMe() {
            axios.delete(`api/v1/system-settings/${this.system_setting.id}`).then(() => {
                this.$emit('deleted');
            })
        },
        updateMe() {
            axios.put(`api/v1/system-settings/${this.system_setting.id}`, {
                value: this.value,
                name: this.system_setting.name
            }).then(() => {
                this.$emit('updated');
            })
        }
    }
}
</script>

<style scoped>

</style>
