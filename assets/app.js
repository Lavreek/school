/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (general.scss in this case)
import './styles/general.scss';

const $ = require('jquery');
const bootstrap = require('bootstrap');

import './scripts/calendar.js';
import './scripts/selectDate.js';

require('bootstrap');
require('bootstrap/js/dist/popover');

// start the Stimulus application
import './bootstrap';

$('.faculty-button').click(function () {
    $.ajax({
        type: "POST",
        url: '/faculty/get',
        success: function (data) {
            let list = $('.faculty-list');
            list.empty();

            list.append('<li class="py-2"><a href="/faculty/form" style="color: green; text-decoration: none;">Создать новый элемент</a></li>');

            for (let i = 0; i < data.count; i++) {

                list.append('<li class="py-2"><a href="/faculty/open/' + data.resources[i].title + '" style="text-decoration: none;">' + data.resources[i].title + '</a></li>');
            }

            const bsCollapse = new bootstrap.Collapse('#faculty-collapse', {
                toggle: true
            })
        },
    });
});

$('.faculty-button-create').on('click', function () {
    let data = new FormData($('#faculty-create')[0]);

    if ($('.collapse').hasClass('show')) {
        const bsCollapse = new bootstrap.Collapse('#messages-collapse', {
            toggle: true
        })
    }

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/faculty/create',
        data: data,
        processData: false,
        contentType: false,
        cache: false,

        success: function (data) {
            let message = $('.display-messages');

            if (typeof data.error != "undefined") {
                message.empty().append('<div className="alert alert-danger" role="alert">Произошла ошибка создания</div>');
            }

            const bsCollapse = new bootstrap.Collapse('#messages-collapse', {
                toggle: true
            })
        }
    });
})

$('.department-button-create').on('click', function () {
    let data = new FormData($('#department-create')[0]);

    if ($('.collapse').hasClass('show')) {
        const bsCollapse = new bootstrap.Collapse('#messages-collapse', {
            toggle: true
        })
    }

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/department/create',
        data: data,
        processData: false,
        contentType: false,
        cache: false,

        success: function (data) {
            let message = $('.display-messages');

            if (typeof data.error != "undefined") {
                message.empty().append('<div className="alert alert-danger" role="alert">Произошла ошибка создания</div>');
            }

            const bsCollapse = new bootstrap.Collapse('#messages-collapse', {
                toggle: true
            })
        }
    });
})
