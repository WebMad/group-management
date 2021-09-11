<template>
    <div>
        <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Название</span>
            </div>
            <input type="text" v-model="add.name" class="form-control">
            <div class="input-group-prepend">
                <span class="input-group-text">Значение</span>
            </div>
            <input type="text" v-model="add.value" class="form-control">
            <div class="input-group-append">
                <button @click="addSetting" type="button" class="btn btn-primary">
                    <i class="bi-plus"></i>
                </button>
            </div>
        </div>
        <system-setting @updated="getSettings" @deleted="getSettings" v-for="system_setting in system_settings"
                        :key="system_setting.id"
                        :system_setting="system_setting"></system-setting>
    </div>
</template>

<script>
export default {
    name: "SystemSettingsList",
    data() {
        return {
            system_settings: [],
            add: {
                name: '',
                value: '',
            }
        }
    },
    mounted() {
        this.getSettings()
    },
    methods: {
        getSettings() {
            axios.get('api/v1/system-settings').then(response => {
                this.system_settings = response.data
            })
        },
        addSetting() {
            axios.post('api/v1/system-settings', {
                name: this.add.name,
                value: this.add.value,
            }).then(() => {
                this.getSettings();
            });
            this.add.name = '';
            this.add.value = '';
        }
    }
}
</script>

<style scoped>

</style>
