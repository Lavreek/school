const $ = require('jquery');
const bootstrap = require('bootstrap')

$('.calendar').on('click', function () {
    let collapseBlock = $('.collapse');

    if (!collapseBlock.hasClass('show')) {
        const bsCollapse = new bootstrap.Collapse('#collapseExample', {
            toggle: true
        })
    }
})