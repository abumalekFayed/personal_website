import Vue from 'vue'
import VueI18n from 'vue-i18n'
import messages from "../locals/index";

Vue.use(VueI18n)

const i18n = new VueI18n({
    locale: localStorage.getItem('current_local')=='en'?"en":"ar",
    silentTranslationWarn: true,
    messages
})

export default i18n