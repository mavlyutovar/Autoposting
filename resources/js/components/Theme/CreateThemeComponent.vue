<template>
    <div>
        <SetThemeName :data="theme"></SetThemeName>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex justify-content-center  col-6">
                <Progress ref="updateProgress"></Progress>
            </div>
            <div class="d-flex justify-content-end">
                <button @click="setTextTheme()" type="button" class="btn btn-secondary m-1">Текст</button>
                <button @click="setAudioTheme()" type="button" class="btn btn-secondary m-1">Аудио</button>
                <button @click="setPictureTheme()" type="button" class="btn btn-secondary m-1">Изображения</button>
                <button @click="setSettingsTheme()" type="button" class="btn btn-info m-1">Сохранить стиль</button>
            </div>
        </div>

        <Component :is="currentComponent"></Component>
    </div>
</template>

<script>

import Progress from './Edit/ProgressCreateTheme'
import SetTextTheme from './Edit/TextThemeComponent'
import SetThemeName from './Edit/SetNameThemeComponent'

Vue.component('set-text-theme', require('./Edit/TextThemeComponent').default);
Vue.component('set-audio-theme', require('./Edit/AudioThemeComponent').default);
Vue.component('set-picture-theme', require('./Edit/PictureThemeComponent').default);
Vue.component('set-setting-theme', require('./Edit/SettingThemeComponent').default);

export default {
    data:function(){
        return{
            currentComponent: "set-text-theme"
        }
    },
    // mounted() {
    //     this.update();
    // },
    components: {
        Progress,
        SetThemeName,
        SetTextTheme
    },
    methods: {
        setTextTheme: function() {
            this.currentComponent = "set-text-theme";
            this.$refs.updateProgress.update();
        },
        setAudioTheme: function() {
            this.currentComponent = "set-audio-theme";
            this.$refs.updateProgress.update();
        },
        setPictureTheme: function() {
            this.currentComponent = "set-picture-theme";
            this.$refs.updateProgress.update();
        },
        setSettingsTheme: function() {
            this.currentComponent = "set-setting-theme";
            this.$refs.updateProgress.update();
        }
    }
}
</script>
