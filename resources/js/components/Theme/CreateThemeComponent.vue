<template>
    <div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex justify-content-center  col-6">
                <Progress ref="updateProgress"></Progress>
            </div>
            <div class="d-flex justify-content-end">
                <button @click="setTextTheme()" type="button" class="btn btn-secondary m-1">Текст</button>
                <button @click="setAudioTheme()" type="button" class="btn btn-secondary m-1">Аудио</button>
                <button @click="setPictureTheme()" type="button" class="btn btn-secondary m-1">Изображения</button>
                <button @click="setSettingsTheme()" type="button" :disabled='saveButton' class="btn btn-primary m-1">Сохранить стиль</button>
            </div>
        </div>

        <Component @updPercent='getPercent' :is="currentComponent"></Component>
    </div>
</template>

<script>

import Progress from './Edit/ProgressCreateTheme'
import SetTextTheme from './Edit/TextThemeComponent'

Vue.component('set-text-theme', require('./Edit/TextThemeComponent').default);
Vue.component('set-audio-theme', require('./Edit/AudioThemeComponent').default);
Vue.component('set-picture-theme', require('./Edit/PictureThemeComponent').default);
Vue.component('set-setting-theme', require('./Edit/SettingThemeComponent').default);

export default {
    data:function(){
        return{
            currentComponent: "set-text-theme",
            percent: 50,
            saveButton: true,
        }
    },
    mounted() {
        this.getPercent();
    },
    components: {
        Progress,
        SetTextTheme
    },
    methods: {
        setTextTheme: function() {
            this.currentComponent = "set-text-theme";
            this.getPercent();
        },
        setAudioTheme: function() {
            this.currentComponent = "set-audio-theme";
            this.getPercent();
        },
        setPictureTheme: function() {
            this.currentComponent = "set-picture-theme";
            this.getPercent();
        },
        setSettingsTheme: function() {
            this.currentComponent = "set-setting-theme";
            this.getPercent();
        },
        cccOL(data) {
            console.log(data)
        },
        getPercent: function() {
            this.percent = 0;
            axios.post('/how-ready', {
                text: this.percent,
                //description: this.description
            }).then((response) => {
                this.percent = response.data;
                this.saveButton = true;
                if(this.percent.data >= 62) {
                    this.saveButton = false;
                }
                this.$refs.updateProgress.update();
            });

        }
    }
}
</script>
