
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./settings');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));
Vue.component('data-table', require('./components/DataTable.vue'));

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);

const app = new Vue({
    el: '#app'
});


/**
 * Run 'npm install jquery-ui --save-dev
 * before editing codes here
 */
import $ from 'jquery';
window.$ = window.jQuery = $;

// import 'jquery-ui-dist/jquery-ui.js';
// import 'jquery-ui/ui/widget.js';
import 'jquery-ui/ui/widgets/progressbar.js';
import 'jquery-ui/ui/widgets/dialog.js';
import 'jquery-ui/ui/widgets/draggable.js';
import 'jquery-ui/ui/widgets/resizable.js';
import 'jquery-ui/ui/widgets/tabs.js';
import 'jquery-ui/ui/widgets/tooltip.js';
import 'jquery-ui/ui/widgets/datepicker.js';
import 'tooltipster/dist/js/tooltipster.bundle.js';
import 'bootstrap-switch/dist/js/bootstrap-switch.js';
import 'jquery-datetimepicker/build/jquery.datetimepicker.full.js';
import 'jquery-sessiontimeout/jquery.sessionTimeout.js';
// import 'datatables.net-buttons/js/buttons.print.js';
// import 'datatables.net-scroller/js/dataTables.scroller.js';
import * as moment from 'moment-timezone-all';

import Split from 'split.js';
window.Split = Split;

import Swal from 'sweetalert2';
window.Swal = Swal;


//import 'jquery-ui-multiselect-widget/src/jquery.multiselect.js';
//import 'jquery-ui-multiselect-widget/src/jquery.multiselect.filter.js';

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});