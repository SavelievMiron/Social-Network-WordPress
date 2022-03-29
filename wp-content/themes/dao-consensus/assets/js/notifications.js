jQuery(function ($) {
  const bell = $('button[data-lp-notifications=button] .lp-badge__dot')
  $('[data-lp-notifications]').on('click', '.lp-notification__close button', function () {
    $(this).parents('.lp-notification').remove();
    if($('[data-lp-notifications] ul.lp-container li').length === 0) {
      $('[data-lp-notifications] ul.lp-container').html(`<li class="topbar no-notifications">Новых уведомлений нету.</li>`)
    }
  });
  const notifications = new Notifications({ selector: $lp('[data-lp-notifications]') })

  notifications.onOpen = function () {
    if (bell.hasClass('active')) {
      $.ajax({
        type: 'post',
        url: ajaxurl,
        data: {
          action: 'dao-get-user-notifications-top',
          _wpnonce: get_user_not_top_nonce
        },
        dataType: 'json',
        beforeSend: function () {
          $('[data-lp-notifications] ul.lp-container').html('<li class="lp-load-notifications"> <span class="lp-loader"> <svg class="lp-loader__circle" width="40" height="40" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"> <circle class="lp-loader__path" fill="none" stroke-width="5" stroke-linecap="round" cx="24" cy="24" r="20" /> </svg> </span> </li>')
        },
        error: function (xhr, ajaxOptions, thrownError) {
          new Snack({ message: 'При получении уведомлений произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
        },
        success: function (response) {
          if (response.success) {
            bell.removeClass('active')
            $('[data-lp-notifications] ul.lp-container').html(response.data.notifications)

            const ids = []
            $('[data-lp-notifications] ul.lp-container li').each(function () {
              ids.push($(this).attr('id'))
            })

            // mark unseen notifications as seen
            if (ids.length !== 0) {
              $.post(ajaxurl, { action: 'dao-mark-unseen-notifications', ids: ids, _wpnonce: mark_unseen_not_nonce }, function (response) {
              })
            }
          }
        }
      })
    }
  }

  setInterval(function () {
    if (!bell.hasClass('active')) {
      $.ajax({
        type: 'post',
        url: ajaxurl,
        data: {
          action: 'dao-check-user-notifications',
          _wpnonce: check_user_not_nonce
        },
        dataType: 'json',
        error: function (xhr, ajaxOptions, thrownError) {
          // new Snack({ message: 'При получении уведомлений произошла ошибка.', variant: 'danger' }).show()
        },
        success: function (response) {
          if (response.success) {
            if (response.data.count != 0) {
              bell.addClass('active')
            }
          }
        }
      })
    }
  }, 60000)
})
