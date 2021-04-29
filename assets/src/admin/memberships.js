import { Datepicker } from 'vanillajs-datepicker';

document.addEventListener('DOMContentLoaded', function() {
    const datepickers = document.querySelectorAll('.ramphor-memberships .ramphor-memberships-panels .v-datepicker');
    datepickers.forEach(function(ele) {
        window[ele.getAttribute('name')] = new Datepicker(ele, {
            format: 'yyyy/mm/dd',
        });
    });
});
