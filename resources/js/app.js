require('./bootstrap.js')
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import {axios} from 'axios';
import CKEditor from '@ckeditor/ckeditor5-vue';
import  vSelect  from "vue-select";
import VueCountdown from '@chenfengyuan/vue-countdown';
import VueSweetalert2 from 'vue-sweetalert2';
import Select2 from 'vue3-select2-component';
import Shepherd from 'shepherd.js';

import 'sweetalert2/dist/sweetalert2.min.css';
createInertiaApp({
        resolve: async name => {
            let page = await import(`./Pages/${name}`)
            page = page.default;
            return page
        },
        setup({ el, App, props, plugin }) {
            createApp({ render: () => h(App, props) })
                .use(plugin)
                .use(axios)
                .use(VueCountdown)
                .use(Select2)
                .use(CKEditor)
                .use(VueSweetalert2)
                .mount(el)
        },

    },

)

