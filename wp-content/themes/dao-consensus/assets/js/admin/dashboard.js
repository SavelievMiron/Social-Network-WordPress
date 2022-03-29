jQuery(function ($) {
    $(document).ready(function () {
        const stat = $('.dao_dashboard_widget_statistics');
        stat.find('.tabs button').on('click', function () {
            const tab = $(this).data('tab')

            stat.find('.tab-content').addClass('hide')
            stat.find('.tab-btn').removeClass('active')

            $(this).addClass('active')
            stat.find(`#content-${tab}`).removeClass('hide').fadeIn()
        })
    })
})
