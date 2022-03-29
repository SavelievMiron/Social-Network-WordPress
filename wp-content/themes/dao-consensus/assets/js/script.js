jQuery(function ($) {
  $(document).ready(function () {
    const path = window.location.pathname

    if (path === '/') {
      new Swiper('.page-home-slider-company-develop', {
        slidesPerView: 3,
        touchRatio: 0.4,
        spaceBetween: 24,
        loop: true,
        loopedSlides: 5,
        navigation: {
          prevEl: '.lp-slider__button.lp-prev',
          nextEl: '.lp-slider__button.lp-next'
        },
        scrollbar: {
          el: '.lp-slider__scrollbar'
        }
      })
    }

    if (path === '/demands/' || path === '/offers/') {
      /* initialize components */
      const search_query = new TextField({ selector: $lp('div#search') })
      const status = new Select({ selector: $lp('div#status'), isMulti: false, searchable: false })
      const person_type = new Select({ selector: $lp('div#person_type'), isMulti: false, searchable: false })
      const categories = new Select({ selector: $lp('div#categories'), isMulti: true, searchable: true })

      /* initialize pagination */
      let options = {
        totalItems: found_posts,
        itemsPerPage: query_vars.posts_per_page,
        visiblePages: 4,
        page: 1,
        centerAlign: true,
        usageStatistics: false,
        firstItemClassName: 'tui-first-child',
        lastItemClassName: 'tui-last-child',
        template: {
          page: '<a href="#" class="btn-page lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">{{page}}</a>',
          currentPage: '<strong class="lp-button-base lp-button-nav lp-size-medium lp-theme-primary lp-variant-filled">{{page}}</strong>',
          moveButton:
                    '<a href="#" class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled lp-nav-{{type}}">' +
                        '<span class="tui-ico-{{type}}">{{type}}</span>' +
                    '</a>',
          disabledMoveButton:
                    '<span class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat tui-is-disabled lp-nav-{{type}}">' +
                        '<span class="tui-ico-{{type}}">{{type}}</span>' +
                    '</span>',
          moreButton:
                    '<a href="#" class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">' +
                        '<span class="tui-ico-ellip">...</span>' +
                    '</a>'
        }
      }

      const pagination = new tui.Pagination('pagination', options)

      if (max_num_pages < 2) {
        $('#pagination').hide()
      }

      pagination.on('afterMove', (event) => {
        query_vars.paged = event.page

        $.ajax({
          type: 'get',
          url: ajaxurl,
          data: {
            action: 'dao-filter-archive-posts',
            query_vars: query_vars,
            filters: $('form.archive-filters').serialize(),
            _wpnonce: nonce
          },
          dataType: 'json',
          error: function (xhr, ajaxOptions, thrownError) {
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            if (response.success) {
              $('#data-container').html(response.data.posts)
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      })

      /* Set onselect events for select elements */
      status.onSelect = (option) => {
        get_results()
      }

      person_type.onSelect = (option) => {
        get_results()
      }

      categories.onSelect = (option) => {
        const tag = option[option.length - 1]
        const html = `<div class="tag lp-grid lp-item lp-chip lp-default lp-variant-outlined" data-text="${tag.text}" data-value="${tag.value}"><span class="lp-chip__label">${tag.text}</span> <button type="button" class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded" data-lp-select="clear"> <i class="lp-icon lp-times-flat"></i> </button> </div>`
        $('.selected-categories').append(html)

        get_results()
      }

      categories.onDeselect = (option) => {
        $('.selected-categories').empty()
        for (let i = 0; option.length > i; i++) {
          const html = `<div class="tag lp-grid lp-item lp-chip lp-default lp-variant-outlined" data-text="${option[i].text}" data-value="${option[i].value}"><span class="lp-chip__label">${option[i].text}</span> <button type="button" class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded" data-lp-select="clear"> <i class="lp-icon lp-times-flat"></i> </button> </div>`
          $('.selected-categories').append(html)
        }
        get_results()
      }

      status.onClear = (option) => {
        get_results()
      }

      person_type.onClear = (option) => {
        get_results()
      }

      categories.onClear = (option) => {
        $('.selected-categories').empty()
        get_results()
      }

      $('.selected-categories').on('click', '.tag button', function () {
        const txt = $(this).parent().data('text')
        const val = $(this).parent().data('value')

        const selectedOptions = categories.selectedOptions.filter(function (option, index, arr) {
          return option.value === val
        })

        selectedOptions.forEach((option) => {
          categories.handleSelectOptions(option.id, option.option, option.value)
        })

        /* remove specific text and value */
        const fil_text = categories.value.text().split(', ').filter(function (value, index, arr) {
          return value !== txt
        })
        const fil_vals = categories.textfield.value.split(',').filter(function (value, index, arr) {
          return value !== val && value !== ''
        })

        /* renew text and value */
        categories.value.text(fil_text.join(', '))
        categories.textfield.value = fil_vals.join(',')

        if (fil_vals.length === 0) {
          categories.handleClearSelect()
        }

        $(this).parent().remove()

        get_results()
      })
      /* ------------------------------ */

      /* AJAX request to filter_posts action */
      function get_results () {
        query_vars.paged = 1

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: {
            action: 'dao-filter-archive-posts',
            filters: $('form.archive-filters').serialize(),
            query_vars: query_vars,
            _wpnonce: nonce
          },
          dataType: 'json',
          error: function (xhr, ajaxOptions, thrownError) {
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            if (response.success) {
              $('#data-container').html(response.data.posts)
              if (response.data.max_num_pages >= 2) {
                if ($('#pagination').is(':hidden')) $('#pagination').show()
                pagination.reset(response.data.found_posts)
              } else {
                if (!$('#pagination').is(':hidden')) $('#pagination').hide()
              }
            } else {
              if (response.data.hasOwnProperty('message')) {
                new Snack({ message: response.data.message, variant: 'danger' }).show()
              } else {
                new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
              }
            }
          }
        })
      }

      $('form.archive-filters').on('submit', function (e) {
        e.preventDefault()
      })

      /* Set events on search field and btn filters */
      $('form.archive-filters input[name=search_query]').on('change', function (e) {
        e.preventDefault()

        query_vars.s = this.value

        get_results()
      })

      /* prevent from page reload when Enter key is pressed */
      $('#search').keypress(function (event) {
        if (event.keyCode == 13) {
          event.preventDefault()
        }
      })

      $('#publication_date').on('click', function () {
        const icon = $(this).children('.lp-icon')

        if (icon.hasClass('lp-triangle-filter')) icon.removeClass('lp-triangle-filter')

        if (!query_vars.hasOwnProperty('orderby')) {
          query_vars.orderby = {}
        } else {
          if (query_vars.orderby.hasOwnProperty('rating')) {
            delete query_vars.orderby.rating
          }
        }

        $('#rating').find('i.lp-icon').attr('class', 'lp-icon lp-postfix lp-triangle-filter')

        if (icon.hasClass('lp-triangle-up')) {
          icon.removeClass('lp-triangle-up').addClass('lp-triangle-down')
          query_vars.orderby.date = 'DESC'
        } else {
          icon.removeClass('lp-triangle-down').addClass('lp-triangle-up')
          query_vars.orderby.date = 'ASC'
        }

        get_results()
      })

      $('#rating').on('click', function () {
        const icon = $(this).children('.lp-icon')

        if (icon.hasClass('lp-triangle-filter')) icon.removeClass('lp-triangle-filter')

        if (!query_vars.hasOwnProperty('orderby')) {
          query_vars.orderby = {}
        } else {
          if (query_vars.orderby.hasOwnProperty('date')) {
            delete query_vars.orderby.date
          }
        }

        $('#publication_date').find('i.lp-icon').attr('class', 'lp-icon lp-postfix lp-triangle-filter')

        if (icon.hasClass('lp-triangle-up')) {
          icon.removeClass('lp-triangle-up').addClass('lp-triangle-down')
          query_vars.orderby.rating = 'DESC'
        } else {
          icon.removeClass('lp-triangle-down').addClass('lp-triangle-up')
          query_vars.orderby.rating = 'ASC'
        }

        get_results()
      })

      $('#data-container').on('click', 'button.add-to-favourites', function (e) {
        const btn = $(this)
        const id = $(this).parents('.lp-card-wrapper').attr('id')
        const icon = '<i class="lp-icon lp-heart-filled"></i>'
        const loader = '<span class="lp-loader"> <svg class="lp-loader__circle" width="25" height="25" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"> <circle class="lp-loader__path" fill="none" stroke-width="5" stroke-linecap="round" cx="24" cy="24" r="20" /> </svg> </span>'

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: {
            action: add_to_fav_action,
            id: id,
            _wpnonce: add_to_fav_nonce
          },
          dataType: 'json',
          beforeSend: function () {
            btn.prop('disabled', true)
            btn.html(loader)
          },
          error: function (xhr) {
            btn.prop('disabled', false)
            btn.html(icon)

            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            btn.prop('disabled', false)
            btn.html(icon)

            if (response.success) {
              btn.removeClass('add-to-favourites').addClass('delete-from-favourites')
              btn.attr('title', 'Удалить из Избранное')
              new Snack({ message: `${response.data.type} "${response.data.title}" добавлено в Избранное.`, variant: 'success' }).show()
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      })
      $('#data-container').on('click', 'button.delete-from-favourites', function (e) {
        const btn = $(this)
        const id = $(this).parents('.lp-card-wrapper').attr('id')
        const icon = '<i class="lp-icon lp-heart-flat"></i>'
        const loader = '<span class="lp-loader"> <svg class="lp-loader__circle" width="25" height="25" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"> <circle class="lp-loader__path" fill="none" stroke-width="5" stroke-linecap="round" cx="24" cy="24" r="20" /> </svg> </span>'

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: {
            action: delete_from_fav_action,
            id: id,
            _wpnonce: delete_from_fav_nonce
          },
          dataType: 'json',
          beforeSend: function () {
            btn.prop('disabled', true)
            btn.html(loader)
          },
          error: function (xhr) {
            btn.prop('disabled', false)
            btn.html(icon)

            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            btn.prop('disabled', false)
            btn.html(icon)
            btn.attr('title', 'Добавить в Избранное')

            if (response.success) {
              btn.removeClass('delete-from-favourites').addClass('add-to-favourites')
              new Snack({ message: `${response.data.type} "${response.data.title}" удалено из Избранное.`, variant: 'success' }).show()
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      })
    }

    if (path === '/transactions/') {
      /* initialize components */
      const search = new TextField({ selector: $lp('div#search') })
      const cryptocurrencies = new Select({ selector: $lp('div#cryptocurrencies'), isMulti: false, searchable: false })

      /* initialize pagination */
      let options = {
        totalItems: found_posts,
        itemsPerPage: query_vars.posts_per_page,
        visiblePages: 4,
        page: 1,
        centerAlign: true,
        usageStatistics: false,
        firstItemClassName: 'tui-first-child',
        lastItemClassName: 'tui-last-child',
        template: {
          page: '<a href="#" class="btn-page lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">{{page}}</a>',
          currentPage: '<strong class="lp-button-base lp-button-nav lp-size-medium lp-theme-primary lp-variant-filled">{{page}}</strong>',
          moveButton:
                    '<a href="#" class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled lp-nav-{{type}}">' +
                        '<span class="tui-ico-{{type}}">{{type}}</span>' +
                    '</a>',
          disabledMoveButton:
                    '<span class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat tui-is-disabled lp-nav-{{type}}">' +
                        '<span class="tui-ico-{{type}}">{{type}}</span>' +
                    '</span>',
          moreButton:
                    '<a href="#" class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">' +
                        '<span class="tui-ico-ellip">...</span>' +
                    '</a>'
        }
      }

      const pagination = new tui.Pagination('pagination', options)

      if (max_num_pages < 2) {
        $('#pagination').hide()
      }

      pagination.on('afterMove', (event) => {
        query_vars.paged = event.page

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: {
            action: action,
            query_vars: query_vars,
            filters: $('form.archive-filters').serialize(),
            _wpnonce: nonce
          },
          dataType: 'json',
          error: function (xhr, ajaxOptions, thrownError) {
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            if (response.success) {
              $('#data-container').html(response.data.posts)
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      })

      cryptocurrencies.onSelect = (option) => {
        get_transactions()
      }

      cryptocurrencies.onClear = (option) => {
        get_transactions()
      }

      $('form.archive-filters').on('submit', function (e) {
        e.preventDefault()
      })

      /* Set events on search field and btn filters */
      $('form.archive-filters input[name=search_query]').on('change', function (e) {
        get_transactions()
      })

      /* prevent from page reload when Enter key is pressed */
      $('form.archive-filters input[name=search_query]').keypress(function (event) {
        if (event.keyCode === 13) {
          event.preventDefault()
        }
      })

      $('#publication_date').on('click', function () {
        const icon = $(this).children('.lp-icon')

        if (icon.hasClass('lp-triangle-filter')) icon.removeClass('lp-triangle-filter')

        if (!query_vars.hasOwnProperty('orderby')) {
          query_vars.orderby = {}
        } else {
          if (query_vars.orderby.hasOwnProperty('total_price')) {
            delete query_vars.orderby.total_price
          }
        }

        $('#total_price').find('i.lp-icon').attr('class', 'lp-icon lp-postfix lp-triangle-filter')

        if (icon.hasClass('lp-triangle-up')) {
          icon.removeClass('lp-triangle-up').addClass('lp-triangle-down')
          query_vars.orderby.date = 'DESC'
        } else {
          icon.removeClass('lp-triangle-down').addClass('lp-triangle-up')
          query_vars.orderby.date = 'ASC'
        }

        get_transactions()
      })

      $('#total_price').on('click', function () {
        const icon = $(this).children('.lp-icon')

        if (icon.hasClass('lp-triangle-filter')) icon.removeClass('lp-triangle-filter')

        if (!query_vars.hasOwnProperty('orderby')) {
          query_vars.orderby = {}
        } else {
          if (query_vars.orderby.hasOwnProperty('date')) {
            delete query_vars.orderby.date
          }
        }

        $('#publication_date').find('i.lp-icon').attr('class', 'lp-icon lp-postfix lp-triangle-filter')

        if (icon.hasClass('lp-triangle-up')) {
          icon.removeClass('lp-triangle-up').addClass('lp-triangle-down')
          query_vars.orderby.total_price = 'DESC'
        } else {
          icon.removeClass('lp-triangle-down').addClass('lp-triangle-up')
          query_vars.orderby.total_price = 'ASC'
        }

        get_transactions()
      })

      function get_transactions () {
        query_vars.paged = 1

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: {
            action: action,
            query_vars: query_vars,
            filters: $('form.archive-filters').serialize(),
            _wpnonce: nonce
          },
          dataType: 'json',
          error: function (xhr, ajaxOptions, thrownError) {
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            if (response.success) {
              $('#data-container').html(response.data.posts)

              if (response.data.max_num_pages >= 2) {
                if ($('#pagination').is(':hidden')) $('#pagination').show()
                pagination.reset(response.data.found_posts)
              } else {
                if (!$('#pagination').is(':hidden')) $('#pagination').hide()
              }
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      }

      function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).attr('href')).select();
        document.execCommand("copy");
        $temp.remove();
      }

      $('#data-container').on('click', 'a.lp-link.lp-share-card', function (e) {
        e.preventDefault()
        copyToClipboard(this)
        const type = ($(this).attr('href').includes('offers')) ? 'предложение' : 'спрос'
        new Snack({message: `Ссылка на ${type} скопирована`, variant: 'success'}).show()
      });

    }

    if (path === '/profile/') {
      /* init components */
      $('.lp-textfield').each(function () {
        new TextField({ selector: $lp(this) })
      })
      $('.lp-card-offer .lp-select').each(function () {
        new Select({ selector: $lp(this), isMulti: false, searchable: false })
      })
      $('#data-container .lp-card-offer').each(function () {
        new OfferCard({ selector: $lp(this) })
      })

      $('[data-lp-modal=add-portfolio] select.categories, [data-lp-modal=edit-portfolio-item] select.categories').select2({
        width: '100%',
        maximumSelectionLength: 5,
        placeholder: 'Выберите категории',
        allowClear: true
      })

      /* change counter of available categories */
      $('[data-lp-modal=add-portfolio] select.categories, [data-lp-modal=edit-portfolio-item] select.categories').on('change', function () {
        const count = $(this).parent().find('.select2-selection__choice').length
        $(this).parent().find('.available-categories .counter').html(5 - count)

        $(this).valid()
      })

      let tinymce_config = {
        wpautop: true,
        theme: 'modern',
        skin: 'lightgray',
        language: 'en',
        formats: {
          alignleft: [
            { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: { textAlign: 'left' } },
            { selector: 'img,table,dl.wp-caption', classes: 'alignleft' }
          ],
          aligncenter: [
            { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: { textAlign: 'center' } },
            { selector: 'img,table,dl.wp-caption', classes: 'aligncenter' }
          ],
          alignright: [
            { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: { textAlign: 'right' } },
            { selector: 'img,table,dl.wp-caption', classes: 'alignright' }
          ],
          strikethrough: { inline: 'del' }
        },
        relative_urls: false,
        remove_script_host: false,
        convert_urls: false,
        browser_spellcheck: true,
        fix_list_elements: true,
        entities: '38,amp,60,lt,62,gt',
        entity_encoding: 'raw',
        keep_styles: false,
        paste_webkit_styles: 'font-weight font-style color',
        preview_styles: 'font-family font-size font-weight font-style text-decoration text-transform',
        tabfocus_elements: ':prev,:next',
        plugins: 'charmap,hr,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs,wpview',
        resize: 'vertical',
        menubar: false,
        indent: false,
        toolbar1: 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,spellchecker,fullscreen,wp_adv',
        toolbar2: 'formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
        toolbar3: '',
        toolbar4: '',
        body_class: 'id post-type-post post-status-publish post-format-standard',
        wpeditimage_disable_captions: false,
        wpeditimage_html5_captions: true
      }

      /* init dropzone */
      const dropzone = $('[data-lp-modal=add-portfolio] .lp-file-upload, [data-lp-modal=edit-portfolio-item] .lp-file-upload')

      dropzone.on('click', function () {
        $(this).find('input[type=file]').get(0).click()
      })

      $('[data-lp-modal=add-portfolio] .lp-file-upload.cover input[type=file], [data-lp-modal=edit-portfolio-item] .lp-file-upload.cover input[type=file]').on('change', function (e) {
        if (this.files.length === 0) {
          return
        }

        handleCover(this.files, e)
        $(this).val('')

        $(this).parents('.lp-file-upload').find('input[type=cover_check]').val('set')
      })

      $('[data-lp-modal=add-portfolio] .lp-file-upload.media-files input[type=file], [data-lp-modal=edit-portfolio-item] .lp-file-upload.media-files input[type=file]').on('change', function (e) {
        if (this.files.length === 0) {
          return
        }

        handleMediaFiles(this.files, e)
        $(this).val('')

        $(this).parents('.lp-file-upload').find('input[name=media_check]').val('set')
      })

      /* prevent file opening */
      dropzone.on('dragenter', function (e) {
        e.preventDefault()
        e.stopPropagation()
        dropzone.addClass('highlighted')
      })
      dropzone.on('dragleave', function (e) {
        e.preventDefault()
        e.stopPropagation()
        dropzone.addClass('highlighted')
      })
      dropzone.on('dragover', function (e) {
        e.preventDefault()
        e.stopPropagation()
        dropzone.removeClass('highlighted')
      })
      dropzone.on('drop', function (e) {
        e.preventDefault()
        e.stopPropagation()
        dropzone.removeClass('highlighted')
      })

      dropzone.on('drop', function (e) {
        const files = e.originalEvent.dataTransfer.files

        if (files.length === 0) {
          return
        }

        if ($(this).hasClass('cover')) {
          handleCover(files, e)
        } else {
          handleMediaFiles(files, e)
        }
      })

      dropzone.on('click', '.image-preview, .lp-file-upload__label', function (e) {
        e.stopPropagation()
      })

      /* cover handler */
      function handleCover (file, e) {
        if (file.length > 1) {
          new Snack({ message: 'Вы можете загрузить только одно изображение в формате jpg, jpeg или png', variant: 'danger' }).show()
          return
        }

        if (validateCover(file[0])) {
          if ($(e.target).parents('.lp-file-upload').find('input[name=cover_check]').val() === 'default') {
            new Snack({ message: 'Обложка уже установлена. Если хотите загрузить новую, удалите текущую.', variant: 'danger' }).show()
            return
          }
          if (formData.getAll('cover').length < 1) {
            previewAndattachCover(file[0], e)
            formData.append('cover', file[0], file[0].name)
            $(e.target).parents('.lp-file-upload').find('input[name=cover_check]').val('set')
            $(e.target).parents('.lp-file-upload').find('input[name=cover_check]').valid()
          } else {
            new Snack({ message: 'Вы можете загрузить только одно изображение', variant: 'danger' }).show()
          }
        }
      }
      function validateCover (cover) {
        const cover_type = cover.type

        const validImgTypes = ['image/jpeg', 'image/jpg', 'image/png']

        /* check file type */
        if (!cover_type.includes('image')) {
          new Snack({ message: 'Вы можете загрузить только изображение', variant: 'danger' }).show()
          return false
        }

        /* check image format */
        if (validImgTypes.indexOf(cover_type) === -1) {
          new Snack({ message: 'Вы можете загрузить изображения только в форматах jpeg, jpg, png', variant: 'danger' }).show()
          return false
        }

        /* check imagge size */
        if (cover.size > 2097152) {
          new Snack({ message: 'Вес изображения не должен превышать 2МВ', variant: 'danger' }).show()
          return false
        }

        /* check if file is already written */
        file = formData.getAll('cover')
        if (file.length !== 0) {
          for (let i = 0; i < cover.length; i++) {
            if (file[i].name === cover.name) {
              new Snack({ message: 'Такой файл уже загружен', variant: 'danger' }).show()
              return false
            }
          }
        }

        return true
      }
      function previewAndattachCover (cover, e) {
        const imgView = $('<div></div>').addClass('image-view')
        /* e.target can be input file and lp-file-upload */
        const dropzone = $(e.target).hasClass('lp-file-upload') ? $(e.target) : $(e.target).parents('.lp-file-upload')
        dropzone.find('.image-preview').append(imgView)

        const reader = new FileReader()

        const img = document.createElement('img')
        const title = $('<span></span>').addClass('title').html(cover.name)
        const size = $('<span></span>').addClass('size').html((cover.size / (1024 * 1024)).toFixed(2) + ' MB')
        const img_view = dropzone.find('.image-preview .image-view:last-child')

        img_view.attr('data-filename', cover.name)
        img_view.append(img)
        img_view.append(title)
        img_view.append(size)
        img_view.append('<span class="close">&times;</span>')

        reader.onload = function (e) {
          img.src = e.target.result
        }
        reader.readAsDataURL(cover)
      }

      /* media files handler */
      function handleMediaFiles (files, e) {
        let length = files.length + $('.lp-file-upload.media-files .image-view.default').length

        if (length > 5) {
          new Snack({ message: 'Вы можете загрузить не больше 5-ти файлов', variant: 'danger' }).show()
          return
        }

        const accepted_files = []
        for (let i = 0, len = files.length; i < len; i++) {
          if (validateMedia(files[i])) {
            accepted_files.push(files[i])
          }
        }

        for (let i = 0, len = accepted_files.length; i < len; i++) {
          length = formData.getAll('media_files[]').length + $('.lp-file-upload.media-files .image-view.default').length
          if (length < 5) {
            previewAndattachMedia(accepted_files[i], e)
            console.log('attach media')
            formData.append('media_files[]', accepted_files[i], accepted_files[i].name)
          } else {
            new Snack({ message: 'Вы можете загрузить не больше 5-ти файлов', variant: 'danger' }).show()
            return
          }
        }
      }
      function validateMedia (media) {
        // check the type
        const media_type = media.type

        const validImgTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif']
        const validVideoTypes = ['video/mp4']

        /* check file type */
        if (!media_type.includes('image') && !media_type.includes('video')) {
          new Snack({ message: 'Вы можете загрузить только изображения или видео', variant: 'danger' }).show()
          return false
        }

        /* check image and video format */
        if (media_type.includes('image') && validImgTypes.indexOf(media_type) === -1) {
          new Snack({ message: 'Вы можете загрузить изображения только в форматах jpeg, jpg, png', variant: 'danger' }).show()
          return false
        }
        if (media_type.includes('video') && validVideoTypes.indexOf(media_type) === -1) {
          new Snack({ message: 'Вы можете загрузить видео только в формате mp4', variant: 'danger' }).show()
          return false
        }

        /* check sizes */
        if (media_type.includes('image') && validImgTypes.indexOf(media_type) !== -1) {
          if (media.size > 2097152) {
            new Snack({ message: 'Вес изображения не должен превышать 2МВ', variant: 'danger' }).show()
            return false
          }
        }
        if (media_type.includes('video') && validVideoTypes.indexOf(media_type) !== -1) {
          if (media.size > 52428800) {
            new Snack({ message: 'Вес видео не должен превышать 50МВ', variant: 'danger' }).show()
            return false
          }
        }

        /* check if file is already written */
        media_files = formData.getAll('media_files[]')
        if (media_files.length !== 0) {
          for (let i = 0; i < media_files.length; i++) {
            if (media_files[i].name === media.name) {
              new Snack({ message: 'Такой файл уже загружен', variant: 'danger' }).show()
              return false
            }
          }
        }

        return true
      }
      function previewAndattachMedia (media, e) {
        // container
        const imgView = $('<div></div>').addClass('image-view')
        /* e.target can be input file and lp-file-upload */
        const dropzone = $(e.target).hasClass('lp-file-upload') ? $(e.target) : $(e.target).parents('.lp-file-upload')
        dropzone.find('.image-preview').append(imgView)

        const reader = new FileReader()

        if (media.type.match('image')) {
          const img = document.createElement('img')
          const title = $('<span></span>').addClass('title').html(media.name)
          const size = $('<span></span>').addClass('size').html((media.size / (1024 * 1024)).toFixed(2) + ' MB')
          const img_view = dropzone.find('.image-preview .image-view:last-child')

          img_view.attr('data-filename', media.name)
          img_view.append(img)
          img_view.append(title)
          img_view.append(size)
          img_view.append('<span class="close">&times;</span>')

          reader.onload = function (e) {
            img.src = e.target.result
          }
          reader.readAsDataURL(media)
        } else {
          reader.onload = function () {
            const blob = new Blob([reader.result], { type: media.type })
            const url = URL.createObjectURL(blob)
            const video = document.createElement('video')
            const title = $('<span></span>').addClass('title').html(media.name)
            const size = $('<span></span>').addClass('size').html((media.size / (1024 * 1024)).toFixed(2) + ' MB')
            const video_view = dropzone.find('.image-preview .image-view:last-child')

            video_view.attr('data-filename', media.name)
            video.src = url
            video.controls = 'controls'
            video_view.append(video)
            video_view.append(title)
            video_view.append(size)
            video_view.append('<span class="close">&times;</span>')
          }
          reader.readAsArrayBuffer(media)
        }
      }

      $('[data-lp-modal=add-portfolio] .lp-file-upload.cover .image-preview').on('click', '.image-view span.close', function (e) {
        /* remove cover file */
        formData.delete('cover')
        /* remove img */
        $(this).parent().remove()
        /* set null to cover checker */
        $('[data-lp-modal=add-portfolio] .lp-file-upload.cover input[name=cover_check]').val('')
        $('[data-lp-modal=add-portfolio] .lp-file-upload.cover input[name=cover_check]').valid()

        e.stopPropagation()
      })

      $('[data-lp-modal=add-portfolio] .lp-file-upload.media-files .image-preview').on('click', '.image-view span.close', function (e) {
        const media_files = formData.getAll('media_files[]')
        const filename = $(this).parent().data('filename')

        /* delete file from array */
        for (let i = 0; i < media_files.length; i++) {
          if (media_files[i].name === filename) {
            media_files.splice(i, 1)
          }
        }

        /* remove old data */
        formData.delete('media_files[]')
        if (media_files.length !== 0) {
          /* write new data */
          for (let i = 0, len = media_files.length; i < len; i++) {
            formData.append('media_files[]', media_files[i], media_files[i].name)
          }
        } else {
          $(this).parents('.lp-file-upload').find('input[type=hidden]').val('')
        }

        /* remove img */
        $(this).parent().remove()

        e.stopPropagation()
      })

      $('[data-lp-modal=edit-portfolio-item] .lp-file-upload.cover .image-preview').on('click', '.image-view span.close', function (e) {
        /* remove cover file */
        formData.delete('cover')
        /* remove img */
        $(this).parent().remove()
        /* set null to cover checker */
        $('[data-lp-modal=edit-portfolio-item] .lp-file-upload.cover input[name=cover_check]').val('')
        $('[data-lp-modal=edit-portfolio-item] .lp-file-upload.cover input[name=cover_check]').valid()

        e.stopPropagation()
      })

      $('[data-lp-modal=edit-portfolio-item] .lp-file-upload.media-files .image-preview').on('click', '.image-view span.close', function (e) {
        const img_preview = $(this).parents('.image-preview')
        const img_view = $(this).parents('.image-view')

        if (!img_view.hasClass('default')) {
          const media_files = formData.getAll('media_files[]')
          const filename = img_view.data('filename')

          /* delete file from array */
          for (let i = 0; i < media_files.length; i++) {
            if (media_files[i].name === filename) {
              media_files.splice(i, 1)
            }
          }

          /* remove old data */
          formData.delete('media_files[]')
          if (media_files.length !== 0) {
            /* write new data */
            for (let i = 0, len = media_files.length; i < len; i++) {
              formData.append('media_files[]', media_files[i], media_files[i].name)
            }
          }
        } else {
          const id = img_view.data('id')
          let val = $('.lp-file-upload.media-files input[name=remove_media_files]').val().split(',')
          val = val.filter(function (v, i) {
            return v !== ''
          }).map(function (v) {
            return parseInt(v, 10)
          })
          val.push(id)
          $('.lp-file-upload.media-files input[name=remove_media_files]').val(val.join(','))
        }

        /* remove img */
        img_view.remove()

        if (img_preview.find('.image-view').length === 0) {
          img_preview.parents('.lp-file-upload').find('input[name=media_check]').val('')
        }

        e.stopPropagation()
      })

      jQuery.extend(jQuery.validator.messages, {
        required: 'Это поле обязательно к заполнению'
      })

      const addPortfolioForm = $('form.add-portfolio-form').validate({
        ignore: [],
        rules: {
          title: {
            required: true,
            minlength: 5,
            maxlength: 200
          },
          description: {
            required: true,
            minlength: 50,
            maxlength: 2000
          },
          'categories[]': 'required',
          cover_check: 'required',
          media_check: 'required'
        },
        errorClass: 'lp-invalid',
        errorElement: 'span',
        validClass: 'lp-valid',
        messages: {
          title: {
            minlength: 'Название работы не должно быть короче 5 символов.',
            maxlength: 'Название работы не должно быть длинее 200 символов.'
          },
          description: {
            minlength: 'Описание работы не должно быть короче 50 символов.',
            maxlength: 'Описание работы не должно быть длинее 2000 символов.'
          },
          'categories[]': {
            required: 'Выберите хотя бы одну категорию.'
          },
          cover_check: {
            required: 'Наличие обложки у работы обязательно.'
          },
          media_checker: {
            required: 'Загрузите хотя бы один медиа файл, который наилучшим образом может презентовать вашу работу.'
          }
        },
        errorPlacement: function (error, element) {
          if ($(element).attr('name') === 'cover_check' || $(element).attr('name') === 'media_check') {
            error.insertAfter($(element).parents('.lp-file-upload'))
            return
          }
          if ($(element).attr('name') === 'description') {
            error.insertAfter($(element))
            return
          }
          if ($(element).hasClass('select2')) {
            error.insertAfter($(element).next())
            return
          }
          error.insertAfter($(element).parents('.lp-textfield'))
        },
        highlight: function (element, errorClass, validClass) {
          if ($(element).attr('name') === 'cover_check' || $(element).attr('name') === 'media_check') {
            $(element).parents('.lp-file-upload').addClass(errorClass).removeClass(validClass)
            return
          }
          if ($(element).attr('name') === 'description') {
            $(element).parent().find('.mce-tinymce').addClass(errorClass).removeClass(validClass)
            return
          }
          if ($(element).hasClass('select2')) {
            $(element).addClass(errorClass).removeClass(validClass)
            return
          }
          $(element).parents('.lp-textfield').addClass(errorClass).removeClass(validClass)
        },
        unhighlight: function (element, errorClass, validClass) {
          if ($(element).attr('name') === 'cover_check' || $(element).attr('name') === 'media_check') {
            $(element).parents('.lp-file-upload').removeClass(errorClass).addClass(validClass)
          }
          if ($(element).attr('name') === 'description') {
            $(element).parent().find('.mce-tinymce').removeClass(errorClass).addClass(validClass)
            return
          }
          if ($(element).hasClass('select2')) {
            $(element).removeClass(errorClass).addClass(validClass)
            return
          }
          $(element).parents('.lp-textfield').removeClass(errorClass).addClass(validClass)
        },
        submitHandler: function (form) {
          const submit_btn = $(form).find('button[type=submit]')
          const loader = submit_btn.next('.lp-loader')

          $(form).find('input:not(.lp-file-upload [type=hidden]:not([name=remove_media_files])):not([name=cover]):not([name=media_files]), textarea:not(.select2-search__field), select').each(function () {
            const name = $(this).attr('name')
            formData.set(name, $(this).val())
          })

          $.ajax({
            type: 'post',
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function () {
              submit_btn.prop('disabled', true)
              loader.removeClass('lp-hide')
            },
            error: function (xhr, ajaxOptions, thrownError) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')

              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            },
            success: function (response) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')

              if (response.success) {
                new Snack({ message: 'Заявка на добавление работы в Портфолио отправлена на модерацию. Ожидайте сообщения о результате.', variant: 'success' }).show()
                addPortfolioModal.handleClose()
              } else {
                if (response.data.hasOwnProperty('message')) {
                  new Snack({ message: response.data.message, variant: 'danger' }).show()
                } else {
                  new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
                }
              }
            }
          })

          return false
        }
      })
      const editPortfolioItemForm = $('form.edit-portfolio-form').validate({
        ignore: [],
        rules: {
          title: {
            required: true,
            minlength: 5,
            maxlength: 200
          },
          description: {
            required: true,
            minlength: 50,
            maxlength: 2000
          },
          'categories[]': 'required',
          cover_check: 'required',
          media_check: 'required'
        },
        errorClass: 'lp-invalid',
        errorElement: 'span',
        validClass: 'lp-valid',
        messages: {
          title: {
            minlength: 'Название работы не должно быть короче 5 символов.',
            maxlength: 'Название работы не должно быть длинее 200 символов.'
          },
          description: {
            minlength: 'Описание работы не должно быть короче 50 символов.',
            maxlength: 'Описание работы не должно быть длинее 2000 символов.'
          },
          'categories[]': {
            required: 'Выберите хотя бы одну категорию.'
          },
          cover_check: {
            required: 'Наличие обложки у работы обязательно.'
          },
          media_checker: {
            required: 'Загрузите хотя бы один медиа файл, который наилучшим образом может презентовать вашу работу.'
          }
        },
        errorPlacement: function (error, element) {
          if ($(element).attr('name') === 'cover_check' || $(element).attr('name') === 'media_check') {
            error.insertAfter($(element).parents('.lp-file-upload'))
            return
          }
          if ($(element).attr('name') === 'description') {
            error.insertAfter($(element))
            return
          }
          if ($(element).hasClass('select2')) {
            error.insertAfter($(element).next())
            return
          }
          error.insertAfter($(element).parents('.lp-textfield'))
        },
        highlight: function (element, errorClass, validClass) {
          if ($(element).attr('name') === 'cover_check' || $(element).attr('name') === 'media_check') {
            $(element).parents('.lp-file-upload').addClass(errorClass).removeClass(validClass)
            return
          }
          if ($(element).attr('name') === 'description') {
            $(element).parent().find('.mce-tinymce').addClass(errorClass).removeClass(validClass)
            return
          }
          if ($(element).hasClass('select2')) {
            $(element).addClass(errorClass).removeClass(validClass)
            return
          }
          $(element).parents('.lp-textfield').addClass(errorClass).removeClass(validClass)
        },
        unhighlight: function (element, errorClass, validClass) {
          if ($(element).attr('name') === 'cover_check' || $(element).attr('name') === 'media_check') {
            $(element).parents('.lp-file-upload').removeClass(errorClass).addClass(validClass)
          }
          if ($(element).attr('name') === 'description') {
            $(element).parent().find('.mce-tinymce').removeClass(errorClass).addClass(validClass)
            return
          }
          if ($(element).hasClass('select2')) {
            $(element).removeClass(errorClass).addClass(validClass)
            return
          }
          $(element).parents('.lp-textfield').removeClass(errorClass).addClass(validClass)
        },
        submitHandler: function (form) {
          const submit_btn = $(form).find('button[type=submit]')
          const loader = submit_btn.next('.lp-loader')

          $(form).find('input:not(.lp-file-upload [type=hidden]:not([name=remove_media_files])):not([name=cover]):not([name=media_files]), textarea:not(.select2-search__field), select').each(function () {
            const name = $(this).attr('name')
            formData.set(name, $(this).val())
          })

          $.ajax({
            type: 'post',
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function () {
              submit_btn.prop('disabled', true)
              loader.removeClass('lp-hide')
            },
            error: function (xhr, ajaxOptions, thrownError) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')

              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            },
            success: function (response) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')

              if (response.success) {
                new Snack({ message: 'Заявка на изменение работы в Портфолио отправлена на модерацию. Ожидайте сообщения о результате.', variant: 'success' }).show()
                editPortfolioItemModal.handleClose();
              } else {
                if (response.data.hasOwnProperty('message')) {
                  new Snack({ message: response.data.message, variant: 'danger' }).show()
                } else {
                  new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
                }
              }
            }
          })

          return false
        }
      })

      const editPortfolioTitle = new TextField({ selector: $lp('[data-lp-modal=edit-portfolio-item] .lp-textfield.title') })

      $('[data-lp-modal=edit-portfolio-item]').on('click', 'button[data-lp-modal=delete]', function () {
        const title = $(this).data('title')
        const item_id = $(this).data('item_id')
        deletePortfolioModal.handleOpen()
        $('[data-lp-modal=delete-portfolio-item] span.title').html(title)
        $('[data-lp-modal=delete-portfolio-item] input[name=item_id]').val(item_id)
      })

      $('[data-lp-modal=delete-portfolio-item] form').on('submit', function (e) {
        e.preventDefault()

        const form = $(this)
        const submit_btn = form.find('button[type=submit]')
        const loader = submit_btn.next('.lp-loader')

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: form.serialize(),
          dataType: 'json',
          beforeSend: function () {
            submit_btn.prop('disabled', true)
            loader.removeClass('lp-hide')
          },
          error: function (xhr, ajaxOptions, thrownError) {
            submit_btn.prop('disabled', false)
            loader.addClass('lp-hide')
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            submit_btn.prop('disabled', false)
            loader.addClass('lp-hide')
            if (response.success) {
              deletePortfolioModal.handleClose()
              editPortfolioItemModal.handleClose()
              new Snack({ message: 'Работа успешно удалена из Портфолио.', variant: 'success' }).show()
              $('.lp-portfolio .lp-portfolio__box#' + response.data.id).remove()
            } else {
              if (response.data.hasOwnProperty('message')) {
                new Snack({ message: response.data.message, variant: 'danger' }).show()
              } else {
                new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
              }
            }
          }
        })
      })

      const addPortfolioModal = new Modal({ selector: $lp('[data-lp-modal=add-portfolio]') })
      const editPortfolioItemModal = new Modal({ selector: $lp('[data-lp-modal=edit-portfolio-item]') })
      const viewPortfolioModal = new Modal({ selector: $lp('[data-lp-modal=view-portfolio]') })
      const deletePortfolioModal = new Modal({ selector: $lp('[data-lp-modal=delete-portfolio-item]') })

      addPortfolioModal.onClose = function () {
        tinymce.activeEditor.setContent('')
        $('form.add-portfolio-form input[name=title]').val('').change()
        $('form.add-portfolio-form .lp-textfield').removeClass('lp-active')
        $('form.add-portfolio-form select.categories').val('').change()
        $('form.add-portfolio-form .lp-file-upload.cover .image-preview').empty()
        $('form.add-portfolio-form .lp-file-upload.media-files .image-preview').empty()
        addPortfolioForm.resetForm()
        formData = new FormData()
      };

      editPortfolioItemModal.onClose = function () {
        tinymce.activeEditor.setContent('')
        $('form.edit-portfolio-form input[name=title]').val('').change()
        $('form.edit-portfolio-form .lp-textfield').removeClass('lp-active')
        $('form.edit-portfolio-form select.categories').val('').change()
        $('form.edit-portfolio-form .lp-file-upload.cover .image-preview').empty()
        $('form.edit-portfolio-form .lp-file-upload.media-files .image-preview').empty()
        editPortfolioItemForm.resetForm()
        formData = new FormData()
      };

      let textarea_editor = null; // for wp.editor

      $('#add-portfolio').on('click', () => {
        addPortfolioModal.handleOpen()
        tinymce_config.setup = function (editor) {
          editor.on('input paste', function (e) {
            this.save()
            $(editor.targetElm).valid()
          })
        }
        wp.editor.remove('description')
        textarea_editor = wp.editor.initialize('description', {
          tinymce: tinymce_config
        })
      })

      $('.lp-portfolio .lp-portfolio__box').on('click', function () {
        const id = $(this).attr('id')

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: {
            action: 'dao-get-portfolio-info',
            id: id,
            type: 'view',
            _wpnonce: get_portfolio_info_nonce
          },
          dataType: 'json',
          error: function (xhr, ajaxOptions, thrownError) {
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            if (response.success) {
              viewPortfolioModal.handleOpen()
              const modal = $('[data-lp-modal=view-portfolio]')
              modal.find('.title').html(response.data.title)
              modal.find('.categories').html(response.data.categories)
              modal.find('.portfolio-content').html(response.data.content)
              modal.find('.portfolio-gallery').html(response.data.media_files)
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      })

      $('.lp-portfolio .lp-portfolio__box').on('click', 'button.lp-card__edit', function (e) {
        e.stopPropagation()

        const btn = $(this)
        const id = $(this).parents('.lp-portfolio__box').attr('id')

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: {
            action: 'dao-get-portfolio-info',
            id: id,
            type: 'edit',
            _wpnonce: get_portfolio_info_nonce
          },
          dataType: 'json',
          beforeSend: function () {
            btn.prop('disabled', true)
          },
          error: function (xhr, ajaxOptions, thrownError) {
            btn.prop('disabled', false)
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            btn.prop('disabled', false)
            if (response.success) {
              editPortfolioItemModal.handleOpen()
              const modal = $('[data-lp-modal=edit-portfolio-item]')
              editPortfolioTitle.value = response.data.title
              tinymce_config.setup = function (editor) {
                editor.on('input paste', function (e) {
                  $('form.edit-portfolio-form').valid()
                })
                editor.on('init', function (e) {
                  editor.setContent(response.data.content)
                  $(e.target.targetElm).val(response.data.content)
                })
              }
              wp.editor.remove('description')
              wp.editor.initialize('description', {
                tinymce: tinymce_config
              })
              modal.find('select.categories').val(response.data.categories)
              modal.find('select.categories').trigger('change')
              modal.find('.available-categories .counter').html(5 - response.data.categories.length)
              modal.find('.lp-file-upload.cover .image-preview').html(response.data.cover)
              modal.find('.lp-file-upload.cover input[type=hidden]').val('default')
              modal.find('.lp-file-upload.media-files .image-preview').html(response.data.media_files.file_list)
              modal.find('.lp-file-upload.media-files input[name=media_check]').val('set')
              modal.find('button[data-lp-modal=delete]').data('title', response.data.title)
              modal.find('button[data-lp-modal=delete]').data('item_id', response.data.item_id)
              modal.find('input[name=item_id]').val(response.data.item_id)
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      })

      new Swiper('.page-profile-slider-testimonails', {
        slidesPerView: 3,
        touchRatio: 0.4,
        spaceBetween: 24,
        navigation: {
          prevEl: '.lp-slider__button.lp-prev',
          nextEl: '.lp-slider__button.lp-next'
        }
      })

      let options = {
        totalItems: profile_cards.found_posts,
        itemsPerPage: profile_cards.posts_per_page,
        visiblePages: 4,
        page: 1,
        centerAlign: true,
        usageStatistics: false,
        firstItemClassName: 'tui-first-child',
        lastItemClassName: 'tui-last-child',
        template: {
          page: '<a href="#" class="btn-page lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">{{page}}</a>',
          currentPage: '<strong class="lp-button-base lp-button-nav lp-size-medium lp-theme-primary lp-variant-filled">{{page}}</strong>',
          moveButton:
                    '<a href="#" class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled lp-nav-{{type}}">' +
                        '<span class="tui-ico-{{type}}">{{type}}</span>' +
                    '</a>',
          disabledMoveButton:
                    '<span class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat tui-is-disabled lp-nav-{{type}}">' +
                        '<span class="tui-ico-{{type}}">{{type}}</span>' +
                    '</span>',
          moreButton:
                    '<a href="#" class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">' +
                        '<span class="tui-ico-ellip">...</span>' +
                    '</a>'
        }
      }

      const pagination = new tui.Pagination('pagination', options)

      if (profile_cards.max_num_pages < 2) {
        $('#pagination').hide()
      }

      pagination.on('afterMove', (event) => {
        query_vars.paged = event.page

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: {
            action: 'dao-get-profile-cards',
            query_vars: query_vars,
            _wpnonce: nonce
          },
          dataType: 'json',
          error: function (xhr, ajaxOptions, thrownError) {
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            if (response.success) {
              $('#data-container').html(response.data.posts)
              $('.lp-select').each(function () {
                new Select({ selector: $lp(this) })
              })
              $('.lp-card-offer').each(function () {
                new OfferCard({ selector: $lp(this) })
              })
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      })

      $('#data-container').on('submit', '[data-lp-modal=delete-card] form', function (e) {
        e.preventDefault()

        const form = $(this)
        const btn = $(this).find('button[type=submit]')
        const loader = btn.next('.lp-loader')

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: btn.parents('.delete-post-form').serialize(),
          dataType: 'json',
          beforeSend: function () {
            btn.prop('disabled', true)
            loader.removeClass('lp-hide')
          },
          error: function (xhr, ajaxOptions, thrownError) {
            btn.prop('disabled', false)
            loader.addClass('lp-hide')

            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            btn.prop('disabled', false)
            loader.addClass('lp-hide')

            if (response.success) {
              if (form.hasClass('demands')) {
                new Snack({ message: 'Ваш спрос успешно удалён.', variant: 'success' }).show()
              } else {
                new Snack({ message: 'Ваше предложение успешно удалено.', variant: 'success' }).show()
              }
              $('#data-container #card-' + response.data.post_id).remove()
              $('html').css('overflow', 'auto')
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      })

      $('#data-container').on('submit', '[data-lp-modal=change-card-status] form', function (e) {
        e.preventDefault()

        const form = $(this)
        const btn = $(this).find('button[type=submit]')
        const loader = btn.next('.lp-loader')

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: form.serialize(),
          dataType: 'json',
          beforeSend: function () {
            btn.prop('disabled', true)
            loader.removeClass('lp-hide')
          },
          error: function (xhr, ajaxOptions, thrownError) {
            btn.prop('disabled', false)
            loader.addClass('lp-hide')

            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            btn.prop('disabled', false)
            loader.addClass('lp-hide')

            if (response.success) {
              if (form.hasClass('demands')) {
                new Snack({ message: 'Статус вашего спроса изменён.', variant: 'success' }).show()
              } else {
                new Snack({ message: 'Статус вашего предложения изменён.', variant: 'success' }).show()
              }
              $('#data-container #card-' + response.data.id + ' .lp-card').removeClass('lp-status-' + response.data.from).addClass('lp-status-' + response.data.to)
              $('#data-container #card-' + response.data.id + ' .lp-card__header .post-status span').html(response.data.text)
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      })

      function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).attr('href')).select();
        document.execCommand("copy");
        $temp.remove();
      }

      $('.lp-common-info__share a.lp-link').on('click', function (e) {
        e.preventDefault()
        copyToClipboard(this)
        new Snack({message: 'Ссылка на профиль скопирована.', variant: 'success'}).show()
      });
    }

    if (path === '/profile/demands/' || path === '/profile/offers/') {
      /* initialize components */
      $('.lp-textfield').each(function () {
        new TextField({ selector: $lp(this) })
      })

      const status = new Select({ selector: $lp('div#status'), isMulti: false, searchable: false })
      const categories = new Select({ selector: $lp('div#categories'), isMulti: true, searchable: true })

      $('#data-container .lp-modal .lp-select').each(function () {
        new Select({ selector: $lp(this), isMulti: false, searchable: false })
      })

      $('.lp-card').each(function () {
        new OfferCard({ selector: $lp(this) })
      })

      /* prevent page reload on submit */
      $('form.filters').on('submit', function (e) {
        e.preventDefault()
      })

      /* prevent page reload when Enter key is pressed */
      $('input[name=search_query]').keypress(function (event) {
        if (event.keyCode == 13) {
          event.preventDefault()
        }
      })

      $('#publication_date').on('click', function () {
        const icon = $(this).children('.lp-icon')

        if (icon.hasClass('lp-triangle-filter')) icon.removeClass('lp-triangle-filter')

        if (!query_vars.hasOwnProperty('orderby')) {
          query_vars.orderby = {}
        }

        if (icon.hasClass('lp-triangle-up')) {
          icon.removeClass('lp-triangle-up').addClass('lp-triangle-down')
          query_vars.orderby.date = 'DESC'
        } else {
          icon.removeClass('lp-triangle-down').addClass('lp-triangle-up')
          query_vars.orderby.date = 'ASC'
        }

        get_posts()
      })

      status.onSelect = (option) => {
        get_posts()
      }

      categories.onSelect = (option) => {
        const tag = option[option.length - 1]
        const html = `<div class="tag lp-grid lp-item lp-chip lp-default lp-variant-outlined" data-text="${tag.text}" data-value="${tag.value}"><span class="lp-chip__label">${tag.text}</span> <button type="button" class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded" data-lp-select="clear"> <i class="lp-icon lp-times-flat"></i> </button> </div>`
        $('.selected-categories').append(html)

        get_posts()
      }

      categories.onDeselect = (option) => {
        $('.selected-categories').empty()
        for (let i = 0; option.length > i; ++i) {
          const html = `<div class="tag lp-grid lp-item lp-chip lp-default lp-variant-outlined" data-text="${option[i].text}" data-value="${option[i].value}"><span class="lp-chip__label">${option[i].text}</span> <button type="button" class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded" data-lp-select="clear"> <i class="lp-icon lp-times-flat"></i> </button> </div>`
          $('.selected-categories').append(html)
        }
        get_posts()
      }

      status.onClear = (option) => {
        get_posts()
      }

      categories.onClear = (option) => {
        $('.selected-categories').empty()
        get_posts()
      }

      $('.selected-categories').on('click', '.tag button', function () {
        const txt = $(this).parent().data('text')
        const val = $(this).parent().data('value')

        const selectedOptions = categories.selectedOptions.filter(function (option, index, arr) {
          return option.value === val
        })

        selectedOptions.forEach((option) => {
          categories.handleSelectOptions(option.id, option.option, option.value)
        })

        /* remove specific text and value */
        const fil_text = categories.value.text().split(', ').filter(function (value, index, arr) {
          return value !== txt
        })
        const fil_vals = categories.textfield.value.split(',').filter(function (value, index, arr) {
          return value !== val && value !== ''
        })

        /* renew text and value */
        categories.value.text(fil_text.join(', '))
        categories.textfield.value = fil_vals.join(',')

        if (fil_vals.length === 0) {
          categories.handleClearSelect()
        }

        $(this).parent().remove()

        get_posts()
      })

      $('#search').on('change', function (e) {
        e.preventDefault()
        get_posts()
      })

      /* initialize pagination */
      let options = {
        totalItems: found_posts,
        itemsPerPage: posts_per_page,
        visiblePages: 4,
        page: 1,
        centerAlign: true,
        usageStatistics: false,
        firstItemClassName: 'tui-first-child',
        lastItemClassName: 'tui-last-child',
        template: {
          page: '<a href="#" class="btn-page lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">{{page}}</a>',
          currentPage: '<strong class="lp-button-base lp-button-nav lp-size-medium lp-theme-primary lp-variant-filled">{{page}}</strong>',
          moveButton:
                    '<a href="#" class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled lp-nav-{{type}}">' +
                        '<span class="tui-ico-{{type}}">{{type}}</span>' +
                    '</a>',
          disabledMoveButton:
                    '<span class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat tui-is-disabled lp-nav-{{type}}">' +
                        '<span class="tui-ico-{{type}}">{{type}}</span>' +
                    '</span>',
          moreButton:
                    '<a href="#" class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">' +
                        '<span class="tui-ico-ellip">...</span>' +
                    '</a>'
        }
      }

      const pagination = new tui.Pagination('pagination', options)

      if (max_num_pages < 2) {
        $('#pagination').hide()
      }

      pagination.on('afterMove', (event) => {
        query_vars.paged = event.page

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: {
            action: action,
            query_vars: query_vars,
            filters: $('form.filters').serialize(),
            _wpnonce: nonce
          },
          dataType: 'json',
          error: function (xhr, ajaxOptions, thrownError) {
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            if (response.success) {
              $('#data-container').html(response.data.posts)
              /* init components */
              $('#data-container .lp-modal .lp-select').each(function () {
                new Select({ selector: $lp(this) })
              })
              $('#data-container .lp-card').each(function () {
                new OfferCard({ selector: $lp(this) })
              })
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      })

      function get_posts () {
        query_vars.paged = 1

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: {
            action: action,
            query_vars: query_vars,
            filters: $('form.filters').serialize(),
            _wpnonce: nonce
          },
          dataType: 'json',
          error: function (xhr, ajaxOptions, thrownError) {
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            if (response.success) {
              $('#data-container').html(response.data.posts)
              /* init Select component */
              $('#data-container .lp-modal .lp-select').each(function () {
                new Select({ selector: $lp(this) })
              })
              /* init cards */
              $('#data-container .lp-card').each(function () {
                new OfferCard({ selector: $lp(this) })
              })
              if (response.data.max_num_pages >= 2) {
                if ($('#pagination').is(':hidden')) $('#pagination').show()
                pagination.reset(response.data.found_posts)
              } else {
                if (!$('#pagination').is(':hidden')) $('#pagination').hide()
              }
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      }

      $('#data-container').on('submit', '[data-lp-modal=delete-card] form', function (e) {
        e.preventDefault()

        const form = $(this)
        const submit_btn = $(this).find('button[type=submit]')
        const loader = submit_btn.next('.lp-loader')

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: form.serialize(),
          dataType: 'json',
          beforeSend: function () {
            submit_btn.prop('disabled', true)
            loader.removeClass('lp-hide')
          },
          error: function (xhr, ajaxOptions, thrownError) {
            submit_btn.prop('disabled', false)
            loader.addClass('lp-hide')
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            submit_btn.prop('disabled', false)
            loader.addClass('lp-hide')

            if (response.success) {              
              if (path === '/profile/demands/') {
                new Snack({ message: 'Ваш спрос успешно удалён.', variant: 'success' }).show()
              } else {
                new Snack({ message: 'Ваше предложение успешно удалено.', variant: 'success' }).show()
              }
              $('#data-container #card-' + response.data.card_id).remove()
              $('html').css('overflow', 'auto')
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      })

      $('#data-container').on('submit', '[data-lp-modal=change-card-status] form', function (e) {
        e.preventDefault()

        const form = $(this)
        const submit_btn = $(this).find('button[type=submit]')
        const loader = submit_btn.next('.lp-loader')

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: form.serialize(),
          dataType: 'json',
          beforeSend: function () {
            submit_btn.prop('disabled', true)
            loader.removeClass('lp-hide')
          },
          error: function (xhr, ajaxOptions, thrownError) {
            submit_btn.prop('disabled', false)
            loader.addClass('lp-hide')

            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            submit_btn.prop('disabled', false)
            loader.addClass('lp-hide')

            if (response.success) {
              $('#data-container #card-' + response.data.id + ' .lp-card').removeClass('lp-status-' + response.data.from).addClass('lp-status-' + response.data.to)
              $('#data-container #card-' + response.data.id + ' .post-status span.lp-chip__label').html(response.data.text)
              if (path === '/profile/demands/') {
                new Snack({ message: 'Статус вашего спроса успешно изменён.', variant: 'success' }).show()
              } else {
                new Snack({ message: 'Статус вашего предложения успешно изменён.', variant: 'success' }).show()
              }
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      })
    }

    if (path === '/profile/in-process/') {

      $('.lp-page').on('submit', '[data-lp-card=completed-modal] form', function (e) {
        e.preventDefault()

        const form = $(this)
        const submit_btn = $(this).find('button[type=submit]')
        const loader = submit_btn.next('.lp-loader')

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: form.serialize(),
          dataType: 'json',
          beforeSend: function () {
            submit_btn.prop('disabled', true)
            loader.removeClass('lp-hide')
          },
          error: function (xhr, ajaxOptions, thrownError) {
            submit_btn.prop('disabled', false)
            loader.addClass('lp-hide')
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            submit_btn.prop('disabled', false)
            loader.addClass('lp-hide')
            if (response.success) {
              submit_btn.prop('disabled', true)
              if (response.data.type === 'offer') {
                new Snack({ message: `Выполнение предложения "${response.data.title}" исполнителем ${response.data.performer} успешно подтверждено.`, variant: 'success' }).show()
              } else {
                new Snack({ message: `Выполнение cпроса "${response.data.title}" исполнителем ${response.data.performer} успешно подтверждено.`, variant: 'success' }).show()
              }
              setTimeout(function () {
                window.location.href = `${submit_testimonial_url}?transaction_id=${response.data.transaction_id}`
              }, 2000)
            } else {
              if (response.data.hasOwnProperty('message')) {
                new Snack({ message: response.data.message, variant: 'danger' }).show()
              } else {
                new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
              }
            }
          }
        })
      })

      $('.lp-page').on('submit', '[data-lp-card=not-completed-modal] form', function (e) {
        e.preventDefault()

        const form = $(this)
        const submit_btn = $(this).find('button[type=submit]')
        const loader = submit_btn.next('.lp-loader')

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: form.serialize(),
          dataType: 'json',
          beforeSend: function () {
            submit_btn.prop('disabled', true)
            loader.removeClass('lp-hide')
          },
          error: function (xhr, ajaxOptions, thrownError) {
            submit_btn.prop('disabled', false)
            loader.addClass('lp-hide')
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            submit_btn.prop('disabled', false)
            loader.addClass('lp-hide')
            if (response.success) {
              submit_btn.prop('disabled', true)
              if (response.data.type === 'offer') {
                new Snack({ message: `Не выполнение предложения "${response.data.title}" исполнителем ${response.data.performer} подтверждено.`, variant: 'success' }).show()
              } else {
                new Snack({ message: `Не выполнение cпроса "${response.data.title}" исполнителем ${response.data.performer} подтверждено.`, variant: 'success' }).show()
              }
              setTimeout(function () {
                // window.location.href = submit_testimonial_url + `?transaction_id=${response.data.id}`
              }, 2000)
            } else {
              if (response.data.hasOwnProperty('message')) {
                new Snack({ message: response.data.message, variant: 'danger' }).show()
              } else {
                new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
              }            
            }
          }
        })
      })

      /* initialize pagination */
      let options = {
        visiblePages: 4,
        page: 1,
        centerAlign: true,
        usageStatistics: false,
        firstItemClassName: 'tui-first-child',
        lastItemClassName: 'tui-last-child',
        template: {
          page: '<a href="#" class="btn-page lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">{{page}}</a>',
          currentPage: '<strong class="lp-button-base lp-button-nav lp-size-medium lp-theme-primary lp-variant-filled">{{page}}</strong>',
          moveButton:
                    '<a href="#" class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled lp-nav-{{type}}">' +
                        '<span class="tui-ico-{{type}}">{{type}}</span>' +
                    '</a>',
          disabledMoveButton:
                    '<span class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat tui-is-disabled lp-nav-{{type}}">' +
                        '<span class="tui-ico-{{type}}">{{type}}</span>' +
                    '</span>',
          moreButton:
                    '<a href="#" class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">' +
                        '<span class="tui-ico-ellip">...</span>' +
                    '</a>'
        }
      }

      let demands_inprocess_pagination = ''
      let offers_inprocess_pagination = ''

      if (typeof demands_inprocess !== 'undefined' && demands_inprocess.found_posts !== 0) {
        /* init components */
        const search = new TextField({ selector: $lp('.demands-inprocess div#search') })

        $('.lp-page .demands-inprocess .lp-card-mine').each(function () {
          new InProcessCard({ selector: $lp(this) })
        })
  
        const demands_type = new Select({ selector: $lp('.demands-inprocess form.filters .type'), isMulti: false, searchable: false })

        demands_type.onSelect = (option) => {
          get_demands_inprocess()
        }

        demands_type.onClear = (option) => {
          get_demands_inprocess()
        }

        $('.demands-inprocess form.filters input[name=search_query]').each(function () {
          $(this).keypress(function (event) {
            if (event.keyCode == 13) {
              event.preventDefault()
            }
          })
          $(this).on('input', function (e) {
            e.preventDefault()

            if (this.value) {
              demands_inprocess.query_vars.s = this.value
            } else {
              delete demands_inprocess.query_vars.s
            }

            get_demands_inprocess()
          })
        })

        /* init pagination */
        options.totalItems = demands_inprocess.found_posts
        options.itemsPerPage = demands_inprocess.posts_per_page

        demands_inprocess_pagination = new tui.Pagination('demands-pagination', options)
        demands_inprocess_pagination.on('afterMove', (event) => {
          demands_inprocess.query_vars.paged = event.page

          $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
              action: demands_action,
              query_vars: demands_inprocess.query_vars,
              filters: $('.demands-inprocess form.filters').serialize(),
              _wpnonce: nonce
            },
            dataType: 'json',
            error: function (xhr, ajaxOptions, thrownError) {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            },
            success: function (response) {
              if (response.success) {
                $('.demands-inprocess #data-container').html(response.data.meetings)
                $('.demands-inprocess #data-container .lp-card-mine').each(function () {
                  new InProcessCard({ selector: $lp(this) })
                })
              } else {
                new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
              }
            }
          })
        })
      }

      if (typeof offers_inprocess !== 'undefined' && offers_inprocess.found_posts !== 0) {
        /* init components */
        const search = new TextField({ selector: $lp('.offers-inprocess div#search') })

        $('.lp-page .offers-inprocess .lp-card-mine').each(function () {
          new InProcessCard({ selector: $lp(this) })
        })

        const offers_inprocess_type = new Select({ selector: $lp('.offers-inprocess form.filters .type'), isMulti: false, searchable: false })

        offers_inprocess_type.onClear = (option) => {
          get_offers_inprocess()
        }

        offers_inprocess_type.onSelect = (option) => {
          get_offers_inprocess()
        }

        $('.offers-inprocess form.filters input[name=search_query]').each(function () {
          $(this).keypress(function (event) {
            if (event.keyCode == 13) {
              event.preventDefault()
            }
          })
          $(this).on('input', function (e) {
            e.preventDefault()

            if (this.value) {
              offers_inprocess.query_vars.s = this.value
            } else {
              delete offers_inprocess.query_vars.s
            }

            get_offers_inprocess()
          })
        })

        /* init pagination */
        options.totalItems = offers_inprocess.found_posts
        options.itemsPerPage = offers_inprocess.posts_per_page

        offers_inprocess_pagination = new tui.Pagination('offers-pagination', options)
        offers_inprocess_pagination.on('afterMove', (event) => {
          offers_inprocess.query_vars.paged = event.page

          $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
              action: offers_action,
              query_vars: offers_inprocess.query_vars,
              filters: $('.offers-inprocess form.filters').serialize(),
              _wpnonce: nonce
            },
            dataType: 'json',
            error: function (xhr, ajaxOptions, thrownError) {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            },
            success: function (response) {
              if (response.success) {
                $('.offers-inprocess #data-container').html(response.data.meetings)
                $('.offers-inprocess #data-container .lp-card-mine').each(function () {
                  new InProcessCard({ selector: $lp(this) })
                })
              } else {
                new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
              }
            }
          })
        })
      }

      if (typeof demands_inprocess !== 'undefined' && demands_inprocess.max_num_pages < 2) {
        $('nav#demands-pagination').hide()
      }

      if (typeof offers_inprocess !== 'undefined' && offers_inprocess.max_num_pages < 2) {
        $('nav#offers-pagination').hide()
      }

      /* init tabs */
      const tabs = new Tabs({ selector: $lp('div#card-types') })

      function get_demands_inprocess () {
        demands_inprocess.query_vars.paged = 1

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: {
            action: demands_action,
            query_vars: demands_inprocess.query_vars,
            filters: $('.demands-inprocess form.filters').serialize(),
            _wpnonce: nonce
          },
          dataType: 'json',
          error: function (xhr, ajaxOptions, thrownError) {
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            if (response.success) {
              $('.demands-inprocess #data-container').html(response.data.cards)
              if (response.data.max_num_pages >= 2) {
                if ($('#demands-pagination').is(':hidden')) $('#demands-pagination').show()
                demands_inprocess_pagination.reset(response.data.found_posts)
              } else {
                if (!$('#demands-pagination').is(':hidden')) $('#demands-pagination').hide()
              }
              $('.demands-inprocess #data-container .lp-card-mine').each(function () {
                new InProcessCard({ selector: $lp(this) })
              })
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      }

      function get_offers_inprocess () {
        offers_inprocess.query_vars.paged = 1

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: {
            action: offers_action,
            query_vars: offers_inprocess.query_vars,
            filters: $('.offers-inprocess form.filters').serialize(),
            _wpnonce: nonce
          },
          dataType: 'json',
          error: function (xhr, ajaxOptions, thrownError) {
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            if (response.success) {
              $('.offers-inprocess #data-container').html(response.data.cards)
              if (response.data.max_num_pages >= 2) {
                if ($('.offers-inprocess #offers-pagination').is(':hidden')) $('#offers-pagination').show()
                offers_inprocess_pagination.reset(response.data.found_posts)
              } else {
                if (!$('.offers-inprocess #offers-pagination').is(':hidden')) $('#offers-pagination').hide()
              }
              $('.offers-inprocess #data-container .lp-card-mine').each(function () {
                new InProcessCard({ selector: $lp(this) })
              })
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      }
    }

    if (path.includes('/demands/edit/') || path.includes('/offers/edit/')) {
      const title = new TextField({ selector: $lp('.title.lp-textfield') })
      // const description = new TextField({selector: $lp('.description.lp-textfield')});
      const total_price = new TextField({ selector: $lp('.total_price.lp-textfield') })
      const cryptocurrency = new Select({ selector: $lp('.cryptocurrency'), isMulti: false, searchable: false })
      const deadline = new TextField({ selector: $lp('.deadline.lp-textfield') })
      const deadline_period = new Select({ selector: $lp('.deadline-period'), isMulti: false, searchable: false })

      $('.lp-page form').on('submit', function (e) {
        e.preventDefault()
      })

      jQuery.extend(jQuery.validator.messages, {
        required: 'Это поле обязательно к заполнению'
      })

      jQuery.validator.addMethod('minSelectedSkills', function (value, element, param) {
        const count = $(element).parent().find('.select2-selection__choice').length
        return count >= param
      }, jQuery.validator.format('Минимальное количество выбранных навыков - 2'))

      $('.lp-page form').validate({
        ignore: [],
        rules: {
          cover_check: 'required',
          title: {
            required: true,
            minlength: 10,
            maxlength: 200
          },
          category: {
            required: true
          },
          description: {
            required: true,
            minlength: 100,
            maxlength: 3000
          },
          total_price: {
            required: true,
            number: true
          },
          cryptocurrency: {
            required: true
          },
          deadline: {
            required: true,
            digits: true
          },
          deadline_period: {
            required: true
          },
          'categories[]': {
            required: true
          },
          'skills[]': {
            required: true
          }
        },
        errorClass: 'lp-invalid',
        errorElement: 'span',
        validClass: 'lp-valid',
        messages: {
          cover_check: {
            required: 'Наличие обложки обязательно'
          },
          title: {
            minlength: 'Название не должно быть короче 10 символов',
            maxlength: 'Название не должно быть длинее 200 символов'
          },
          description: {
            minlength: 'Описание не должно быть короче 100 символов',
            minlength: 'Описание не должно быть длинее 3000 символов'
          },
          total_price: {
            number: 'Введите число'
          },
          deadline: {
            digits: 'Введите целое число'
          },
          category: 'Выберите категорию',
          cryptocurrency: 'Выберите формат расчёта',
          deadline_period: 'Выберите период'
        },
        errorPlacement: function (error, element) {
          if ($(element).attr('name') === 'cover_check') {
            error.insertAfter($(element).parents('.lp-file-upload'))
            return
          }
          if ($(element).hasClass('select2')) {
            error.insertAfter($(element).next())
            return
          }
          error.insertAfter($(element).parents('.lp-textfield'))
        },
        highlight: function (element, errorClass, validClass) {
          if ($(element).attr('name') === 'cover_check') {
            $(element).parents('.lp-file-upload').addClass(errorClass).removeClass(validClass)
            return
          }
          if ($(element).hasClass('select2')) {
            $(element).addClass(errorClass).removeClass(validClass)
            return
          }
          $(element).parents('.lp-textfield').addClass(errorClass).removeClass(validClass)
        },
        unhighlight: function (element, errorClass, validClass) {
          if ($(element).attr('name') === 'cover_check') {
            $(element).parents('.lp-file-upload').removeClass(errorClass).addClass(validClass)
            return
          }
          if ($(element).hasClass('select2')) {
            $(element).removeClass(errorClass).addClass(validClass)
            return
          }
          $(element).parents('.lp-textfield').removeClass(errorClass).addClass(validClass)
        },
        submitHandler: function (form) {
          const submit_btn = $(form).find('button[type=submit]')
          const loader = submit_btn.next('.lp-loader')

          $(form).find('input:not(.lp-file-upload [type=hidden]:not([name=remove_media_files])):not([name=cover]):not([name=media_files]), textarea:not(.select2-search__field), select').each(function () {
            formData.set($(this).attr('name'), $(this).val())
          })

          $.ajax({
            type: 'post',
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function () {
              submit_btn.prop('disabled', true)
              loader.removeClass('lp-hide')
            },
            error: function (xhr, ajaxOptions, thrownError) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')

              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            },
            success: function (response) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')

              if (response.success) {
                new Snack({ message: 'Ваша заявка успешно отправлена.', variant: 'success' }).show()
              } else {
                if (response.data.hasOwnProperty('message')) {
                  new Snack({ message: response.data.message, variant: 'danger' }).show()
                } else {
                  new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
                }
              }
            }
          })

          return false
        }
      })

      /* init Select2 */
      $('select.skills').select2({
        width: '100%',
        maximumSelectionLength: 20,
        placeholder: 'Выберите навыки',
        allowClear: true,
        tags: true
      })

      $('select.categories').each(function () {
        $(this).select2({
          width: '100%',
          maximumSelectionLength: 5,
          placeholder: 'Выберите категории',
          allowClear: true
        })
      })

      /* change count of available skills and categories */
      $('select.skills').on('change', function () {
        const count = $(this).parent().find('.select2-selection__choice').length
        $(this).parent().find('.available-skills #count').text(20 - count)

        $(this).valid()
      })

      $('select.categories').on('change', function () {
        const count = $(this).parent().find('.select2-selection__choice').length
        $(this).parent().find('.available-categories #count').text(5 - count)

        $(this).valid()
      })

      /* init dropzone */
      const dropzone = $('.lp-file-upload')

      dropzone.on('click', function () {
        $(this).find('input[type=file]').get(0).click()
      })

      $('.lp-file-upload.cover input[type=file]').on('change', function (e) {
        if (this.files.length === 0) {
          return
        }

        handleCover(this.files, e)
        $(this).val('')

        $(this).parents('.lp-file-upload').find('input[type=cover_check]').val('set')
      })

      $('.lp-file-upload.media-files input[type=file]').on('change', function (e) {
        if (this.files.length === 0) {
          return
        }

        handleMediaFiles(this.files, e)
        $(this).val('')
      })

      /* prevent file opening */
      dropzone.on('dragenter', function (e) {
        e.preventDefault()
        e.stopPropagation()
        dropzone.addClass('highlighted')
      })
      dropzone.on('dragleave', function (e) {
        e.preventDefault()
        e.stopPropagation()
        dropzone.addClass('highlighted')
      })
      dropzone.on('dragover', function (e) {
        e.preventDefault()
        e.stopPropagation()
        dropzone.removeClass('highlighted')
      })
      dropzone.on('drop', function (e) {
        e.preventDefault()
        e.stopPropagation()
        dropzone.removeClass('highlighted')
      })

      /*  */
      dropzone.on('drop', function (e) {
        const files = e.originalEvent.dataTransfer.files

        if (files.length === 0) {
          return
        }

        if ($(this).hasClass('cover')) {
          handleCover(files, e)
        } else {
          handleMediaFiles(files, e)
        }
      })

      /* */
      dropzone.on('click', '.image-preview, .lp-file-upload__label', function (e) {
        e.stopPropagation()
      })

      /* cover handler */
      function handleCover (file, e) {
        if (file.length > 1) {
          new Snack({ message: 'Вы можете загрузить только одно изображение в формате jpg, jpeg или png', variant: 'danger' }).show()
          return
        }

        if (validateCover(file[0])) {
          if ($(e.target).parents('.lp-file-upload').find('input[name=cover_check]').val() === 'default') {
            new Snack({ message: 'Обложка уже установлена. Если хотите загрузить новую, удалите текущую.', variant: 'danger' }).show()
            return
          }
          if (formData.getAll('cover').length < 1) {
            previewAndattachCover(file[0], e)
            formData.append('cover', file[0], file[0].name)
            $(e.target).parents('.lp-file-upload').find('input[name=cover_check]').val('set')
            $(e.target).parents('.lp-file-upload').find('input[name=cover_check]').valid()
          } else {
            new Snack({ message: 'Вы можете загрузить только одно изображение', variant: 'danger' }).show()
          }
        }
      }
      function validateCover (cover) {
        const cover_type = cover.type

        const validImgTypes = ['image/jpeg', 'image/jpg', 'image/png']

        /* check file type */
        if (!cover_type.includes('image')) {
          new Snack({ message: 'Вы можете загрузить только изображение', variant: 'danger' }).show()
          return false
        }

        /* check image format */
        if (validImgTypes.indexOf(cover_type) === -1) {
          new Snack({ message: 'Вы можете загрузить изображения только в форматах jpeg, jpg, png', variant: 'danger' }).show()
          return false
        }

        /* check imagge size */
        if (cover.size > 2097152) {
          new Snack({ message: 'Вес изображения не должен превышать 2МВ', variant: 'danger' }).show()
          return false
        }

        /* check if file is already written */
        file = formData.getAll('cover')
        if (file.length !== 0) {
          for (let i = 0; i < cover.length; i++) {
            if (file[i].name === cover.name) {
              new Snack({ message: 'Такой файл уже загружен', variant: 'danger' }).show()
              return false
            }
          }
        }

        return true
      }
      function previewAndattachCover (cover, e) {
        const imgView = $('<div></div>').addClass('image-view')
        /* e.target can be input file and lp-file-upload */
        const dropzone = $(e.target).hasClass('lp-file-upload') ? $(e.target) : $(e.target).parents('.lp-file-upload')
        dropzone.find('.image-preview').append(imgView)

        const reader = new FileReader()

        const img = document.createElement('img')
        const title = $('<span></span>').addClass('title').html(cover.name)
        const size = $('<span></span>').addClass('size').html((cover.size / (1024 * 1024)).toFixed(2) + ' MB')
        const img_view = dropzone.find('.image-preview .image-view:last-child')

        img_view.attr('data-filename', cover.name)
        img_view.append(img)
        img_view.append(title)
        img_view.append(size)
        img_view.append('<span class="close">&times;</span>')

        reader.onload = function (e) {
          img.src = e.target.result
        }
        reader.readAsDataURL(cover)
      }

      /* media files handler */
      function handleMediaFiles (files, e) {
        let length = files.length + $('.lp-file-upload.media-files .image-view.default').length

        if (length > 5) {
          new Snack({ message: 'Вы можете загрузить не больше 5-ти файлов', variant: 'danger' }).show()
          return
        }

        const accepted_files = []
        for (let i = 0, len = files.length; i < len; i++) {
          if (validateMedia(files[i])) {
            accepted_files.push(files[i])
          }
        }

        for (let i = 0, len = accepted_files.length; i < len; i++) {
          length = formData.getAll('media_files[]').length + $('.lp-file-upload.media-files .image-view.default').length
          if (length < 5) {
            previewAndattachMedia(accepted_files[i], e)
            formData.append('media_files[]', accepted_files[i], accepted_files[i].name)
          } else {
            new Snack({ message: 'Вы можете загрузить не больше 5-ти файлов', variant: 'danger' }).show()
            return
          }
        }
      }
      function validateMedia (media) {
        // check the type
        const media_type = media.type

        const validImgTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif']
        const validVideoTypes = ['video/mp4']

        /* check file type */
        if (!media_type.includes('image') && !media_type.includes('video')) {
          new Snack({ message: 'Вы можете загрузить только изображения или видео', variant: 'danger' }).show()
          return false
        }

        /* check image and video format */
        if (media_type.includes('image') && validImgTypes.indexOf(media_type) === -1) {
          new Snack({ message: 'Вы можете загрузить изображения только в форматах jpeg, jpg, png', variant: 'danger' }).show()
          return false
        }
        if (media_type.includes('video') && validVideoTypes.indexOf(media_type) === -1) {
          new Snack({ message: 'Вы можете загрузить видео только в формате mp4', variant: 'danger' }).show()
          return false
        }

        /* check sizes */
        if (media_type.includes('image') && validImgTypes.indexOf(media_type) !== -1) {
          if (media.size > 2097152) {
            new Snack({ message: 'Вес изображения не должен превышать 2МВ', variant: 'danger' }).show()
            return false
          }
        }
        if (media_type.includes('video') && validVideoTypes.indexOf(media_type) !== -1) {
          if (media.size > 52428800) {
            new Snack({ message: 'Вес видео не должен превышать 50МВ', variant: 'danger' }).show()
            return false
          }
        }

        /* check if file is already written */
        media_files = formData.getAll('media_files[]')
        if (media_files.length !== 0) {
          for (let i = 0; i < media_files.length; i++) {
            if (media_files[i].name === media.name) {
              new Snack({ message: 'Такой файл уже загружен', variant: 'danger' }).show()
              return false
            }
          }
        }

        return true
      }
      function previewAndattachMedia (media, e) {
        // container
        const imgView = $('<div></div>').addClass('image-view')
        /* e.target can be input file and lp-file-upload */
        const dropzone = $(e.target).hasClass('lp-file-upload') ? $(e.target) : $(e.target).parents('.lp-file-upload')
        dropzone.find('.image-preview').append(imgView)

        const reader = new FileReader()

        if (media.type.match('image')) {
          const img = document.createElement('img')
          const title = $('<span></span>').addClass('title').html(media.name)
          const size = $('<span></span>').addClass('size').html((media.size / (1024 * 1024)).toFixed(2) + ' MB')
          const img_view = dropzone.find('.image-preview .image-view:last-child')

          img_view.attr('data-filename', media.name)
          img_view.append(img)
          img_view.append(title)
          img_view.append(size)
          img_view.append('<span class="close">&times;</span>')

          reader.onload = function (e) {
            img.src = e.target.result
          }
          reader.readAsDataURL(media)
        } else {
          reader.onload = function () {
            const blob = new Blob([reader.result], { type: media.type })
            const url = URL.createObjectURL(blob)
            const video = document.createElement('video')
            const title = $('<span></span>').addClass('title').html(media.name)
            const size = $('<span></span>').addClass('size').html((media.size / (1024 * 1024)).toFixed(2) + ' MB')
            const video_view = dropzone.find('.image-preview .image-view:last-child')

            video_view.attr('data-filename', media.name)
            video.src = url
            video.controls = 'controls'
            video_view.append(video)
            video_view.append(title)
            video_view.append(size)
            video_view.append('<span class="close">&times;</span>')
          }
          reader.readAsArrayBuffer(media)
        }
      }

      $('.lp-file-upload.cover .image-preview').on('click', '.image-view span.close', function (e) {
        /* remove cover file */
        formData.delete('cover')
        /* remove img */
        $(this).parent().remove()
        /* set null to cover checker */
        $('.lp-file-upload.cover input[name=cover_check]').val('')
        $('.lp-file-upload.cover input[name=cover_check]').valid()

        e.stopPropagation()
      })

      $('.lp-file-upload.media-files .image-preview').on('click', '.image-view span.close', function (e) {
        const img_view = $(this).parent()

        if (!img_view.hasClass('default')) {
          const media_files = formData.getAll('media_files[]')
          const filename = img_view.data('filename')

          /* delete file from array */
          for (let i = 0; i < media_files.length; i++) {
            if (media_files[i].name === filename) {
              media_files.splice(i, 1)
            }
          }

          /* remove old data */
          formData.delete('media_files[]')
          if (media_files.length !== 0) {
            /* write new data */
            for (let i = 0, len = media_files.length; i < len; i++) {
              formData.append('media_files[]', media_files[i], media_files[i].name)
            }
          } else {
            $(this).parents('.lp-file-upload').find('input[type=hidden]').val('')
          }
        } else {
          const id = img_view.data('id')
          let val = $('input[name=remove_media_files]').val().split(',')
          val = val.filter(function (v, i) {
            return v !== ''
          }).map(function (v) {
            return parseInt(v, 10)
          })
          console.log(val)
          val.push(id)
          $('input[name=remove_media_files]').val(val.join(','))
        }

        /* remove img */
        img_view.remove()

        e.stopPropagation()
      })
    }

    if (path === '/create-demand/') {
      /* init components for demand application */
      const title1 = new TextField({ selector: $lp('form.form-demand .title') })
      const total_price1 = new TextField({ selector: $lp('form.form-demand .total_price') })
      const cryptocurrency1 = new Select({ selector: $lp('form.form-demand .cryptocurrency'), isMulti: false, searchable: false })
      const deadline1 = new TextField({ selector: $lp('form.form-demand .deadline') })
      const deadline_period1 = new Select({ selector: $lp('form.form-demand .deadline-period'), isMulti: false, searchable: false })

      /* init components for offer application */
      const title2 = new TextField({ selector: $lp('form.form-offer .title') })
      const total_price2 = new TextField({ selector: $lp('form.form-offer .total_price') })
      const cryptocurrency2 = new Select({ selector: $lp('form.form-offer .cryptocurrency'), isMulti: false, searchable: false })
      const deadline2 = new TextField({ selector: $lp('form.form-offer .deadline') })
      const deadline_period2 = new Select({ selector: $lp('form.form-offer .deadline-period'), sisMulti: false, searchable: false })

      let tinymce_config = {
        wpautop: true,
        theme: 'modern',
        skin: 'lightgray',
        language: 'en',
        formats: {
          alignleft: [
            { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: { textAlign: 'left' } },
            { selector: 'img,table,dl.wp-caption', classes: 'alignleft' }
          ],
          aligncenter: [
            { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: { textAlign: 'center' } },
            { selector: 'img,table,dl.wp-caption', classes: 'aligncenter' }
          ],
          alignright: [
            { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: { textAlign: 'right' } },
            { selector: 'img,table,dl.wp-caption', classes: 'alignright' }
          ],
          strikethrough: { inline: 'del' }
        },
        relative_urls: false,
        remove_script_host: false,
        convert_urls: false,
        browser_spellcheck: true,
        fix_list_elements: true,
        entities: '38,amp,60,lt,62,gt',
        entity_encoding: 'raw',
        keep_styles: false,
        paste_webkit_styles: 'font-weight font-style color',
        preview_styles: 'font-family font-size font-weight font-style text-decoration text-transform',
        tabfocus_elements: ':prev,:next',
        plugins: 'charmap,hr,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs,wpview',
        resize: 'vertical',
        menubar: false,
        indent: false,
        toolbar1: 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,spellchecker,fullscreen,wp_adv',
        toolbar2: 'formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
        toolbar3: '',
        toolbar4: '',
        body_class: 'id post-type-post post-status-publish post-format-standard',
        wpeditimage_disable_captions: false,
        wpeditimage_html5_captions: true,
        setup: function(editor) {
          editor.on('input paste', function(e) {
            this.save()
            $(editor.targetElm).valid()
          });
        }
      }

      const urlParams = new URLSearchParams(window.location.search)

      if (urlParams.get('type')) {
        if (urlParams.get('type') === 'demand') {
          wp.editor.initialize('demanddescription', {
            tinymce: tinymce_config
          })
        }
        if (urlParams.get('type') === 'offer') {
          wp.editor.initialize('offerdescription', {
            tinymce: tinymce_config
          })
        }
      } else {
        wp.editor.initialize('demanddescription', {
          tinymce: tinymce_config
        })
      }

      $('.lp-page-create-offer').on('submit', 'form', function (e) {
        e.preventDefault()
      })

      cryptocurrency1.onSelect = (data) => {
        $(data.option).parents('.lp-select').find('input[type=hidden]').valid()
      }
      cryptocurrency2.onSelect = (data) => {
        $(data.option).parents('.lp-select').find('input[type=hidden]').valid()
      }

      deadline_period1.onSelect = (data) => {
        $(data.option).parents('.lp-select').find('input[type=hidden]').valid()
      }
      deadline_period2.onSelect = (data) => {
        $(data.option).parents('.lp-select').find('input[type=hidden]').valid()
      }

      jQuery.extend(jQuery.validator.messages, {
        required: 'Это поле обязательно к заполнению'
      })

      jQuery.validator.addMethod('minSelectedSkills', function (value, element, param) {
        const count = $(element).parent().find('.select2-selection__choice').length
        return count >= param
      }, jQuery.validator.format('Минимальное количество выбранных навыков - 1'))

      const configuration = {
        ignore: [],
        rules: {
          cover_check: 'required',
          title: {
            required: true,
            minlength: 10,
            maxlength: 200
          },
          category: {
            required: true
          },
          description: {
            required: true,
            minlength: 100,
            maxlength: 3000
          },
          total_price: {
            required: true,
            number: true
          },
          cryptocurrency: {
            required: true
          },
          deadline: {
            required: true,
            digits: true
          },
          deadline_period: {
            required: true
          },
          'categories[]': {
            required: true
          },
          'skills[]': {
            required: true
          }
        },
        errorClass: 'lp-invalid',
        errorElement: 'span',
        validClass: 'lp-valid',
        messages: {
          cover_check: {
            required: 'Наличие обложки обязательно'
          },
          title: {
            minlength: 'Название не должно быть короче 10 символов',
            maxlength: 'Описание не должно быть длинее 200 символов'
          },
          description: {
            minlength: 'Описание не должно быть короче 100 символов',
            maxlength: 'Описание не должно быть длинее 3000 символов'
          },
          total_price: {
            number: 'Введите число'
          },
          deadline: {
            digits: 'Введите целое число'
          },
          cryptocurrency: 'Выберите формат расчёта',
          deadline_period: 'Выберите период'
        },
        errorPlacement: function (error, element) {
          if ($(element).hasClass('file-checker')) {
            error.insertAfter($(element).parents('.lp-file-upload'))
            return
          }
          if ($(element).hasClass('wp-editor-area')) {
            error.insertAfter($(element).parent().find('.mce-tinymce'))
            return
          }
          if ($(element).hasClass('select2')) {
            error.insertAfter($(element).next())
            return
          }
          error.insertAfter($(element).parents('.lp-textfield'))
        },
        highlight: function (element, errorClass, validClass) {
          if ($(element).hasClass('file-checker')) {
            $(element).parents('.lp-file-upload').addClass(errorClass).removeClass(validClass)
            return
          }
          if ($(element).hasClass('wp-editor-area')) {
            $(element).parent().find('.mce-tinymce').addClass(errorClass).removeClass(validClass)
            return
          }
          if ($(element).hasClass('select2')) {
            $(element).addClass(errorClass).removeClass(validClass)
            return
          }
          $(element).parents('.lp-textfield').addClass(errorClass).removeClass(validClass)
        },
        unhighlight: function (element, errorClass, validClass) {
          if ($(element).hasClass('file-checker')) {
            $(element).parents('.lp-file-upload').removeClass(errorClass).addClass(validClass)
            return
          }
          if ($(element).hasClass('wp-editor-area')) {
            $(element).parent().find('.mce-tinymce').removeClass(errorClass).addClass(validClass)
            return
          }
          if ($(element).hasClass('select2')) {
            $(element).removeClass(errorClass).addClass(validClass)
            return
          }
          $(element).parents('.lp-textfield').removeClass(errorClass).addClass(validClass)
        },
        submitHandler: function (form) {
          const submit_btn = $(form).find('button[type=submit]')
          const loader = submit_btn.next('.lp-loader')

          $(form).find('input:not(.lp-file-upload [type=hidden]):not([name=cover]):not([name=media_files]), textarea:not(.select2-search__field), select').each(function () {
            const name = $(this).attr('name')
            formData.set(name, $(this).val())
          })

          $.ajax({
            type: 'post',
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function () {
              submit_btn.prop('disabled', true)
              loader.removeClass('lp-hide')
            },
            error: function (xhr, ajaxOptions, thrownError) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')

              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            },
            success: function (response) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')

              if (response.success) {
                new Snack({ message: 'Ваша заявка успешно отправлена.', variant: 'success' }).show()
                window.location.href = profile_url
              } else {
                if (response.data.hasOwnProperty('message')) {
                  new Snack({ message: response.data.message, variant: 'danger' }).show()
                } else {
                  new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
                }
              }
            }
          })

          return false
        }
      }

      const formDemand = $('form.form-demand').validate(configuration)
      const formOffer = $('form.form-offer').validate(configuration)

      /* init Select2 */
      $('select.skills').each(function () {
        $(this).select2({
          width: '100%',
          maximumSelectionLength: 20,
          placeholder: 'Выберите навыки',
          allowClear: true,
          tags: true
        })
      })

      $('select.categories').each(function () {
        $(this).select2({
          width: '100%',
          maximumSelectionLength: 5,
          placeholder: 'Выберите категории',
          allowClear: true
        })
      })

      $('select.categories').on('change', function () {
        const counter = $(this).parent().find('.select2-selection__choice').length
        $(this).parent().find('.available-categories #count').text(5 - counter)

        $(this).valid()
      })

      /* change counter of available skills */
      $('select.skills').on('change', function () {
        const counter = $(this).parent().find('.select2-selection__choice').length
        $(this).parent().find('.available-skills #count').text(20 - counter)

        $(this).valid()
      })

      /* init dropzone */
      const dropzone = $('.lp-file-upload')

      dropzone.on('click', function () {
        $(this).find('input[type=file]').get(0).click()
      })

      $('.lp-file-upload.cover input[type=file]').on('change', function (e) {
        if (this.files.length === 0) {
          return
        }

        handleCover(this.files, e)
        $(this).val('')

        $(this).parents('.lp-file-upload').find('input[type=cover_check]').val('set')
      })

      $('.lp-file-upload.media-files input[type=file]').on('change', function (e) {
        if (this.files.length === 0) {
          return
        }

        handleMediaFiles(this.files, e)
        $(this).val('')

        // $(this).parents('.lp-file-upload').find('input[type=hidden]').val('set');
      })

      /* prevent file opening */
      dropzone.on('dragenter', function (e) {
        e.preventDefault()
        e.stopPropagation()
        dropzone.addClass('highlighted')
      })
      dropzone.on('dragleave', function (e) {
        e.preventDefault()
        e.stopPropagation()
        dropzone.addClass('highlighted')
      })
      dropzone.on('dragover', function (e) {
        e.preventDefault()
        e.stopPropagation()
        dropzone.removeClass('highlighted')
      })
      dropzone.on('drop', function (e) {
        e.preventDefault()
        e.stopPropagation()
        dropzone.removeClass('highlighted')
      })

      /*  */
      dropzone.on('drop', function (e) {
        const files = e.originalEvent.dataTransfer.files

        if (files.length === 0) {
          return
        }

        if ($(this).hasClass('cover')) {
          handleCover(files, e)
        } else {
          handleMediaFiles(files, e)
        }
      })

      /* */
      dropzone.on('click', '.image-preview, .lp-file-upload__label', function (e) {
        e.stopPropagation()
      })

      /* cover handler */
      function handleCover (file, e) {
        if (file.length > 1) {
          new Snack({ message: 'Вы можете загрузить только одно изображение в формате jpg, jpeg или png', variant: 'danger' }).show()
          return
        }

        if (validateCover(file[0])) {
          if (formData.getAll('cover').length < 1) {
            previewAndattachCover(file[0], e)
            formData.append('cover', file[0], file[0].name)
            $(e.target).parents('.lp-file-upload').find('input[name=cover_check]').val('set')
            $(e.target).parents('.lp-file-upload').find('input[name=cover_check]').valid()
          } else {
            new Snack({ message: 'Вы можете загрузить только одно изображение', variant: 'danger' }).show()
          }
        }
      }

      function validateCover (cover) {
        const cover_type = cover.type

        const validImgTypes = ['image/jpeg', 'image/jpg', 'image/png']

        /* check file type */
        if (!cover_type.includes('image')) {
          new Snack({ message: 'Вы можете загрузить только изображение', variant: 'danger' }).show()
          return false
        }

        /* check image format */
        if (validImgTypes.indexOf(cover_type) === -1) {
          new Snack({ message: 'Вы можете загрузить изображения только в форматах jpeg, jpg, png', variant: 'danger' }).show()
          return false
        }

        /* check imagge size */
        if (cover.size > 2097152) {
          new Snack({ message: 'Вес изображения не должен превышать 2МВ', variant: 'danger' }).show()
          return false
        }

        /* check if file is already written */
        file = formData.getAll('cover')
        if (file.length !== 0) {
          for (let i = 0; i < cover.length; i++) {
            if (file[i].name === cover.name) {
              new Snack({ message: 'Такой файл уже загружен', variant: 'danger' }).show()
              return false
            }
          }
        }

        return true
      }
      function previewAndattachCover (cover, e) {
        const imgView = $('<div></div>').addClass('image-view')
        /* e.target can be input file and lp-file-upload */
        const dropzone = $(e.target).hasClass('lp-file-upload') ? $(e.target) : $(e.target).parents('.lp-file-upload')
        dropzone.find('.image-preview').append(imgView)

        const reader = new FileReader()

        const img = document.createElement('img')
        const title = $('<span></span>').addClass('title').html(cover.name)
        const size = $('<span></span>').addClass('size').html((cover.size / (1024 * 1024)).toFixed(2) + ' MB')
        const img_view = dropzone.find('.image-preview .image-view:last-child')

        img_view.attr('data-filename', cover.name)
        img_view.append(img)
        img_view.append(title)
        img_view.append(size)
        img_view.append('<span class="close">&times;</span>')

        reader.onload = function (e) {
          img.src = e.target.result
        }
        reader.readAsDataURL(cover)
      }

      /* media files handler */
      function handleMediaFiles (files, e) {
        if (files.length > 5) {
          new Snack({ message: 'Вы можете загрузить не больше 5-ти файлов', variant: 'danger' }).show()
          return
        }

        const accepted_files = []
        for (let i = 0, len = files.length; i < len; i++) {
          if (validateMedia(files[i])) {
            accepted_files.push(files[i])
          }
        }

        for (let i = 0, len = accepted_files.length; i < len; i++) {
          if (formData.getAll('media_files[]').length < 5) {
            previewAndattachMedia(accepted_files[i], e)
            formData.append('media_files[]', accepted_files[i], accepted_files[i].name)
          } else {
            new Snack({ message: 'Вы можете загрузить не больше 5-ти файлов', variant: 'danger' }).show()
            return
          }
        }
      }
      function validateMedia (media) {
        // check the type
        const media_type = media.type

        const validImgTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif']
        const validVideoTypes = ['video/mp4']

        /* check file type */
        if (!media_type.includes('image') && !media_type.includes('video')) {
          new Snack({ message: 'Вы можете загрузить только изображения или видео', variant: 'danger' }).show()
          return false
        }

        /* check image and video format */
        if (media_type.includes('image') && validImgTypes.indexOf(media_type) === -1) {
          new Snack({ message: 'Вы можете загрузить изображения только в форматах jpeg, jpg, png', variant: 'danger' }).show()
          return false
        }
        if (media_type.includes('video') && validVideoTypes.indexOf(media_type) === -1) {
          new Snack({ message: 'Вы можете загрузить видео только в формате mp4', variant: 'danger' }).show()
          return false
        }

        /* check sizes */
        if (media_type.includes('image') && validImgTypes.indexOf(media_type) !== -1) {
          if (media.size > 2097152) {
            new Snack({ message: 'Вес изображения не должен превышать 2МВ', variant: 'danger' }).show()
            return false
          }
        }
        if (media_type.includes('video') && validVideoTypes.indexOf(media_type) !== -1) {
          if (media.size > 52428800) {
            new Snack({ message: 'Вес видео не должен превышать 50МВ', variant: 'danger' }).show()
            return false
          }
        }

        /* check if file is already written */
        media_files = formData.getAll('media_files[]')
        if (media_files.length !== 0) {
          for (let i = 0; i < media_files.length; i++) {
            if (media_files[i].name === media.name) {
              new Snack({ message: 'Такой файл уже загружен', variant: 'danger' }).show()
              return false
            }
          }
        }

        return true
      }
      function previewAndattachMedia (media, e) {
        // container
        const imgView = $('<div></div>').addClass('image-view')
        /* e.target can be input file and lp-file-upload */
        const dropzone = $(e.target).hasClass('lp-file-upload') ? $(e.target) : $(e.target).parents('.lp-file-upload')
        dropzone.find('.image-preview').append(imgView)

        const reader = new FileReader()

        if (media.type.match('image')) {
          const img = document.createElement('img')
          const title = $('<span></span>').addClass('title').html(media.name)
          const size = $('<span></span>').addClass('size').html((media.size / (1024 * 1024)).toFixed(2) + ' MB')
          const img_view = dropzone.find('.image-preview .image-view:last-child')

          img_view.attr('data-filename', media.name)
          img_view.append(img)
          img_view.append(title)
          img_view.append(size)
          img_view.append('<span class="close">&times;</span>')

          reader.onload = function (e) {
            img.src = e.target.result
          }
          reader.readAsDataURL(media)
        } else {
          reader.onload = function () {
            const blob = new Blob([reader.result], { type: media.type })
            const url = URL.createObjectURL(blob)
            const video = document.createElement('video')
            const title = $('<span></span>').addClass('title').html(media.name)
            const size = $('<span></span>').addClass('size').html((media.size / (1024 * 1024)).toFixed(2) + ' MB')
            const video_view = dropzone.find('.image-preview .image-view:last-child')

            video_view.attr('data-filename', media.name)
            video.src = url
            video.controls = 'controls'
            video_view.append(video)
            video_view.append(title)
            video_view.append(size)
            video_view.append('<span class="close">&times;</span>')
          }
          reader.readAsArrayBuffer(media)
        }
      }

      $('.lp-file-upload.cover .image-preview').on('click', '.image-view span.close', function (e) {
        /* remove cover file */
        formData.delete('cover')
        /* remove img */
        $(this).parent().remove()
        /* set null to cover checker */
        $('.lp-file-upload.cover input[name=cover_check]').val('')
        $('.lp-file-upload.cover input[name=cover_check]').valid()

        e.stopPropagation()
      })

      $('.lp-file-upload.media-files .image-preview').on('click', '.image-view span.close', function (e) {
        const media_files = formData.getAll('media_files[]')
        const filename = $(this).parent().data('filename')

        /* delete file from array */
        for (let i = 0; i < media_files.length; i++) {
          if (media_files[i].name === filename) {
            media_files.splice(i, 1)
          }
        }

        /* remove old data */
        formData.delete('media_files[]')
        if (media_files.length !== 0) {
          /* write new data */
          for (let i = 0, len = media_files.length; i < len; i++) {
            formData.append('media_files[]', media_files[i], media_files[i].name)
          }
        } else {
          $(this).parents('.lp-file-upload').find('input[type=hidden]').val('')
        }

        /* remove img */
        $(this).parent().remove()

        e.stopPropagation()
      })

      /* init tabs */
      const tabs = new Tabs({ selector: $lp('div#tabs-form') })

      /* execute on tab change */
      let chosen_id = 0
      tabs.onSelect = (option) => {
        formData = new FormData()

        $('.lp-page .lp-file-upload .image-preview').empty()
        $('.lp-page .lp-file-upload .file-checker').val('')

        if (option.id === 0 && chosen_id !== 0) {
          chosen_id = 0

          title2.value = ''
          tinymce.activeEditor.setContent('')
          wp.editor.remove('demanddescription')
          wp.editor.initialize('demanddescription', {
            tinymce: tinymce_config
          })
          total_price2.value = ''
          cryptocurrency2.handleClearSelect()
          deadline2.value = ''
          deadline_period2.handleClearSelect()

          formDemand.resetForm()
          $('form.form-demand').find('input:not(.lp-file-upload [type=hidden]):not([name=cover]):not([name=media_files]), textarea:not(.select2-search__field), select').each(function () {
            $(this).removeClass('lp-invalid')
          })
          $('form.form-demand select.skills, form.form-demand select.categories').val([]).change()
          /* fix */
          $('form.form-demand select.skills, form.form-demand select.categories').each(function () {
            $(this).removeClass('lp-invalid')
            $(this).parent().find('span.lp-invalid').remove()
          })
        } else if (option.id === 1 && chosen_id !== 1) {
          chosen_id = 1

          title1.value = ''
          tinymce.activeEditor.setContent('')
          wp.editor.remove('offerdescription')
          wp.editor.initialize('offerdescription', {
            tinymce: tinymce_config
          })
          total_price1.value = ''
          cryptocurrency1.handleClearSelect()
          deadline1.value = ''
          deadline_period1.handleClearSelect()

          formOffer.resetForm()
          $('form.form-offer').find('input:not(.lp-file-upload [type=hidden]):not([name=cover]):not([name=media_files]), textarea:not(.select2-search__field), select').each(function () {
            $(this).removeClass('lp-invalid')
          })
          $('form.form-offer select.skills, form.form-offer select.categories').val([]).change()
          /* fix */
          $('form.form-offer select.skills, form.form-offer select.categories').each(function () {
            $(this).removeClass('lp-invalid')
            $(this).parent().find('span.lp-invalid').remove()
          })
        }
      }
    }

    if (path === '/profile/meetings/') {
      $('form.filters .lp-textfield').each(function () {
        new TextField({ selector: $lp(this) })
      })

      $('table.meetings-table').each(function () {
        new MeetingsTable({ selector: $lp(this) })
      })

      /* initialize pagination */
      let options = {
        visiblePages: 4,
        page: 1,
        centerAlign: true,
        usageStatistics: false,
        firstItemClassName: 'tui-first-child',
        lastItemClassName: 'tui-last-child',
        template: {
          page: '<a href="#" class="btn-page lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">{{page}}</a>',
          currentPage: '<strong class="lp-button-base lp-button-nav lp-size-medium lp-theme-primary lp-variant-filled">{{page}}</strong>',
          moveButton:
                    '<a href="#" class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled lp-nav-{{type}}">' +
                        '<span class="tui-ico-{{type}}">{{type}}</span>' +
                    '</a>',
          disabledMoveButton:
                    '<span class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat tui-is-disabled lp-nav-{{type}}">' +
                        '<span class="tui-ico-{{type}}">{{type}}</span>' +
                    '</span>',
          moreButton:
                    '<a href="#" class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">' +
                        '<span class="tui-ico-ellip">...</span>' +
                    '</a>'
        }
      }

      let sent_inv_pagination = ''
      let rec_inv_pagination = ''

      if (sent_inv.found_posts !== 0) {
        /* init components */
        const sent_inv_format = new Select({ selector: $lp('.sent-invitations form.filters .format'), isMulti: false, searchable: false })
        const sent_inv_status = new Select({ selector: $lp('.sent-invitations form.filters .status'), isMulti: false, searchable: false })
        const sent_inv_user = new Select({ selector: $lp('.sent-invitations form.filters .user'), isMulti: false, searchable: false })

        sent_inv_format.onSelect = (option) => {
          get_sent_invs()
        }
        sent_inv_status.onSelect = (option) => {
          get_sent_invs()
        }
        sent_inv_user.onSelect = (option) => {
          get_sent_invs()
        }
        sent_inv_format.onClear = (option) => {
          get_sent_invs()
        }
        sent_inv_status.onClear = (option) => {
          get_sent_invs()
        }
        sent_inv_user.onClear = (option) => {
          get_sent_invs()
        }

        $('.sent-invitations form.filters .search').on('change', function (e) {
          e.preventDefault()

          if (this.value) {
            sent_inv.query_vars.s = $(this).val()
          } else {
            delete sent_inv.query_vars.s
          }

          get_sent_invs()
        })

        $('.sent-invitations form.filters .search').keypress(function (e) {
          if (e.keyCode == 13) {
            e.preventDefault()
          }
        })

        $('.sent-invitations .meetings-table .title-order').on('click', function () {
          const icon = $(this).children('.lp-icon')

          if (!$('.sent-invitations .meetings-table .datetime-order .lp-icon').hasClass('lp-triangle-filter')) {
            $('.sent-invitations .meetings-table .datetime-order .lp-icon').attr('class', 'lp-icon lp-triangle-filter')
          }

          if (icon.hasClass('lp-triangle-filter')) icon.removeClass('lp-triangle-filter')

          if (icon.hasClass('lp-triangle-up')) {
            icon.removeClass('lp-triangle-up').addClass('lp-triangle-down')
            sent_inv.query_vars.orderby.title = 'DESC'
          } else {
            icon.removeClass('lp-triangle-down').addClass('lp-triangle-up')
            sent_inv.query_vars.orderby.title = 'ASC'
          }

          if (sent_inv.query_vars.orderby.hasOwnProperty('datetime')) {
            delete sent_inv.query_vars.orderby.datetime
          }

          get_sent_invs()
        })

        $('.sent-invitations .meetings-table .datetime-order').on('click', function () {
          const icon = $(this).children('.lp-icon')

          if (!$('.sent-invitations .meetings-table .title-order .lp-icon').hasClass('lp-triangle-filter')) {
            $('.sent-invitations .meetings-table .title-order .lp-icon').attr('class', 'lp-icon lp-triangle-filter')
          }

          if (icon.hasClass('lp-triangle-filter')) icon.removeClass('lp-triangle-filter')

          if (icon.hasClass('lp-triangle-up')) {
            icon.removeClass('lp-triangle-up').addClass('lp-triangle-down')
            sent_inv.query_vars.orderby.datetime = 'DESC'
          } else {
            icon.removeClass('lp-triangle-down').addClass('lp-triangle-up')
            sent_inv.query_vars.orderby.datetime = 'ASC'
          }

          if (sent_inv.query_vars.orderby.hasOwnProperty('title')) {
            delete sent_inv.query_vars.orderby.title
          }

          get_sent_invs()
        })

        /* init pagination */
        options.totalItems = sent_inv.found_posts,
        options.itemsPerPage = sent_inv.posts_per_page,

        sent_inv_pagination = new tui.Pagination('sent-inv-pagination', options)
        sent_inv_pagination.on('afterMove', (event) => {
          sent_inv.query_vars.paged = event.page

          $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
              action: action,
              query_vars: sent_inv.query_vars,
              filters: $('.sent-invitations form.filters').serialize(),
              _wpnonce: nonce
            },
            dataType: 'json',
            error: function (xhr, ajaxOptions, thrownError) {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            },
            success: function (response) {
              if (response.success) {
                $('#data-container').html(response.data.meetings)
                new MeetingsTable({ selector: $lp('.sent-invitations .meetings-table') })
              } else {
                new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
              }
            }
          })
        })
      }

      if (rec_inv.found_posts !== 0) {
        /* init components */
        const rec_inv_format = new Select({ selector: $lp('.received-invitations form.filters .format'), isMulti: false, searchable: false })
        const rec_inv_status = new Select({ selector: $lp('.received-invitations form.filters .status'), isMulti: false, searchable: false })
        const rec_inv_user = new Select({ selector: $lp('.received-invitations form.filters .user'), isMulti: false, searchable: false })

        rec_inv_format.onSelect = (option) => {
          get_rec_invs()
        }
        rec_inv_status.onSelect = (option) => {
          get_rec_invs()
        }
        rec_inv_user.onSelect = (option) => {
          get_rec_invs()
        }
        rec_inv_format.onClear = (option) => {
          get_rec_invs()
        }
        rec_inv_status.onClear = (option) => {
          get_rec_invs()
        }
        rec_inv_user.onClear = (option) => {
          get_rec_invs()
        }

        $('.received-invitations form.filters .search').on('input', function (e) {
          e.preventDefault()

          if (this.value) {
            rec_inv.query_vars.s = $(this).val()
          } else {
            delete rec_inv.query_vars.s
          }

          get_rec_invs()
        })

        $('.received-invitations form.filters .search').keypress(function (e) {
          if (e.keyCode == 13) {
            e.preventDefault()
          }
        })

        $('.received-invitations .meetings-table .title-order').on('click', function () {
          const icon = $(this).children('.lp-icon')

          if (icon.hasClass('lp-triangle-filter')) icon.removeClass('lp-triangle-filter')

          if (icon.hasClass('lp-triangle-up')) {
            icon.removeClass('lp-triangle-up').addClass('lp-triangle-down')
            rec_inv.query_vars.orderby.title = 'DESC'
          } else {
            icon.removeClass('lp-triangle-down').addClass('lp-triangle-up')
            rec_inv.query_vars.orderby.title = 'ASC'
          }

          get_rec_invs()
        })

        $('.received-invitations .meetings-table .datetime-order').on('click', function () {
          const icon = $(this).children('.lp-icon')

          if (icon.hasClass('lp-triangle-up')) {
            icon.removeClass('lp-triangle-up').addClass('lp-triangle-down')
            rec_inv.query_vars.orderby.datetime = 'DESC'
          } else {
            icon.removeClass('lp-triangle-down').addClass('lp-triangle-up')
            rec_inv.query_vars.orderby.datetime = 'ASC'
          }

          get_rec_invs()
        })

        /* init pagination */
        options.totalItems = rec_inv.found_posts
        options.itemsPerPage = rec_inv.posts_per_page

        rec_inv_pagination = new tui.Pagination('rec-inv-pagination', options)
        rec_inv_pagination.on('afterMove', (event) => {
          rec_inv.query_vars.paged = event.page

          $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
              action: action,
              query_vars: rec_inv.query_vars,
              filters: $('.received-invitations form.filters').serialize(),
              _wpnonce: nonce
            },
            dataType: 'json',
            error: function (xhr, ajaxOptions, thrownError) {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            },
            success: function (response) {
              if (response.success) {
                $('#data-container').html(response.data.meetings)
                new MeetingsTable({ selector: $lp('.received-invitations .meetings-table') })
              } else {
                new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
              }
            }
          })
        })
      }

      if (sent_inv.max_num_pages < 2) {
        $('nav#sent-inv-pagination').hide()
      }

      if (rec_inv.max_num_pages < 2) {
        $('nav#rec-inv-pagination').hide()
      }

      /* init tabs */
      const tabs = new Tabs({ selector: $lp('div#invitation-types') })

      function get_sent_invs () {
        sent_inv.query_vars.paged = 1

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: {
            action: 'dao-get-profile-meetings-sent',
            query_vars: sent_inv.query_vars,
            filters: $('.sent-invitations form.filters').serialize(),
            _wpnonce: nonce
          },
          dataType: 'json',
          error: function (xhr, ajaxOptions, thrownError) {
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            if (response.success) {
              $('.sent-invitations .meetings-table #data-container').html(response.data.meetings)
              new MeetingsTable({ selector: $lp('.sent-invitations .meetings-table') })
              if (response.data.max_num_pages >= 2) {
                if ($('#sent-inv-pagination').is(':hidden')) $('#sent-inv-pagination').show()
                sent_inv_pagination.reset(response.data.found_posts)
              } else {
                if (!$('#sent-inv-pagination').is(':hidden')) $('#sent-inv-pagination').hide()
              }
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      }

      function get_rec_invs () {
        rec_inv.query_vars.paged = 1

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: {
            action: 'dao-get-profile-meetings-received',
            query_vars: rec_inv.query_vars,
            filters: $('.received-invitations form.filters').serialize(),
            _wpnonce: nonce
          },
          dataType: 'json',
          error: function (xhr, ajaxOptions, thrownError) {
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            if (response.success) {
              $('.received-invitations .meetings-table #data-container').html(response.data.meetings)
              new MeetingsTable({ selector: $lp('.received-invitations .meetings-table') })
              if (response.data.max_num_pages >= 2) {
                if ($('#rec-inv-pagination').is(':hidden')) $('#rec-inv-pagination').show()
                rec_inv_pagination.reset(response.data.found_posts)
              } else {
                if (!$('#rec-inv-pagination').is(':hidden')) $('#rec-inv-pagination').hide()
              }
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      }

      /* meeting info */
      const invited = new TextField({ selector: $lp('[data-lp-modal=view-meeting] .invited') })
      const venue = new TextField({ selector: $lp('[data-lp-modal=view-meeting] .venue') })
      const desc = new TextField({ selector: $lp('[data-lp-modal=view-meeting] .description') })

      /* init modals */
      const viewModal = new Modal({ selector: $lp('[data-lp-modal=view-meeting]') })
      const acceptModal = new Modal({ selector: $lp('[data-lp-modal=accept-meeting]') })
      const acceptRescheduleModal = new Modal({ selector: $lp('[data-lp-modal=accept-reschedule-meeting]') })
      const rejectModal = new Modal({ selector: $lp('[data-lp-modal=reject-meeting]') })
      const rejectRescheduleModal = new Modal({ selector: $lp('[data-lp-modal=reject-reschedule-meeting]') })
      const cancelModal = new Modal({ selector: $lp('[data-lp-modal=cancel-meeting]') })

      const datetime = new TextField({ selector: $lp('#datetime') })
      const date = new Date()
      date.setDate(date.getDate() - 1)
      const datePicker = new DatePicker({
        selector: $lp('[data-lp-modal]#date-picker #datepicker'),
        options: {
          minDate: date,
          maxDate: new Date('31.12.2022')
        }
      })
      const datepickerModal = new Modal({ selector: $lp('[data-lp-modal]#date-picker') })

      datepickerModal.onClose = function () {
        datetime.value = ''
      }

      $('#datetime').on('click', () => {
        datepickerModal.handleOpen()

        const input_field = $(this).find('input[name=datetime]')

        datePicker.onSelect = (day) => {
          const formatter = new Intl.DateTimeFormat('ru')
          let date = formatter.format(day.data.Date)
          if (day.time !== null) {
            date += ` ${day.time}`
          }
          datetime.value = date
          input_field.valid()
        }
      })

      $('[data-lp-modal=reschedule-meeting] form').validate({
        ignore: [],
        rules: {
          datetime: 'required'
        },
        errorClass: 'lp-invalid',
        errorElement: 'span',
        validClass: 'lp-valid',
        messages: {
          datetime: {
            required: 'Укажите новые дату и время встречи'
          }
        },
        errorPlacement: function (error, element) {
          error.insertAfter($(element).parents('.lp-textfield'))
        },
        highlight: function (element, errorClass, validClass) {
          $(element).parents('.lp-textfield').addClass(errorClass).removeClass(validClass)
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).parents('.lp-textfield').removeClass(errorClass).addClass(validClass)
        },
        submitHandler: function (form) {
          const submit_btn = $(form).find('button[type=submit]')
          loader = submit_btn.next('.lp-loader')

          $.ajax({
            type: 'post',
            url: ajaxurl,
            data: $(form).serialize(),
            dataType: 'json',
            beforeSend: function () {
              submit_btn.prop('disabled', true)
              loader.removeClass('lp-hide')
            },
            error: function (xhr, ajaxOptions, thrownError) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            },
            success: function (response) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')

              if (response.success) {
                rescheduleModal.handleClose()
                new Snack({ message: 'Предложение о переносе встречи отправлено второму учаснику.', variant: 'success' }).show()
              } else {
                if (response.data.hasOwnProperty('message')) {
                  new Snack({ message: response.data.message, variant: 'danger' }).show()
                } else {
                  new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
                }
              }
            }
          })

          return false
        }
      })

      const rescheduleModal = new Modal({ selector: $lp('[data-lp-modal=reschedule-meeting]') })

      $('.lp-container[data-lp-tabs]').on('click', '.lp-options li.view', function () {
        const meeting = $(this).parents('.meeting')
        const id = meeting.attr('id')

        $.ajax({
          type: 'get',
          url: ajaxurl,
          data: {
            action: 'dao-get-meeting-info',
            id: id,
            _wpnonce: get_meetinginfo_nonce
          },
          dataType: 'json',
          error: function () {
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            if (response.success) {
              viewModal.handleOpen()
              const modal = $('[data-lp-modal=view-meeting]')
              modal.find('.title').text(response.data.title)
              modal.find('.time').text(response.data.time)
              modal.find('.date').text(response.data.date)
              modal.find('.format').text(response.data.format)
              modal.find('.status').text(response.data.status)
              invited.value = response.data.invited
              venue.value = response.data.venue
              desc.value = response.data.desc
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      })

      $('.lp-container[data-lp-tabs]').on('click', '.lp-options li.confirm', function () {
        const meeting = $(this).parents('.meeting')
        const id = meeting.attr('id')
        const name = meeting.data('invitor')

        acceptModal.handleOpen()

        $('[data-lp-modal=accept-meeting] input[name=meeting_id]').val(id)
        $('[data-lp-modal=accept-meeting] span.invitor').html(name)
      })

      $('.lp-container[data-lp-tabs]').on('click', '.lp-options li.confirm-reschedule', function () {
        const meeting = $(this).parents('.meeting')
        const id = meeting.attr('id')
        const title = meeting.find('td.title span').html()

        acceptRescheduleModal.handleOpen()

        $('[data-lp-modal=accept-reschedule-meeting] input[name=meeting_id]').val(id)
        $('[data-lp-modal=accept-reschedule-meeting] span.title').html(title)
      })

      $('.lp-container[data-lp-tabs]').on('click', '.lp-options li.reject', function () {
        const meeting = $(this).parents('.meeting')
        const id = meeting.attr('id')
        const name = meeting.data('invitor')

        rejectModal.handleOpen()

        $('[data-lp-modal=reject-meeting] input[name=meeting_id]').val(id)
        $('[data-lp-modal=reject-meeting] span.invitor').html(name)
      })

      $('.lp-container[data-lp-tabs]').on('click', '.lp-options li.reject-reschedule', function () {
        const meeting = $(this).parents('.meeting')
        const id = meeting.attr('id')
        const title = meeting.find('td.title span').html()

        rejectRescheduleModal.handleOpen()

        $('[data-lp-modal=reject-reschedule-meeting] input[name=meeting_id]').val(id)
        $('[data-lp-modal=reject-reschedule-meeting] span.title').html(title)
      })

      $('.lp-container[data-lp-tabs]').on('click', '.lp-options li.cancel', function () {
        const meeting = $(this).parents('.meeting')
        const id = meeting.attr('id')
        const name = meeting.data('invitor')

        cancelModal.handleOpen()

        $('[data-lp-modal=cancel-meeting] input[name=meeting_id]').val(id)
        $('[data-lp-modal=cancel-meeting] span.invitor').html(name)
      })

      $('.lp-container[data-lp-tabs]').on('click', '.lp-options li.reschedule', function () {
        rescheduleModal.handleOpen()

        const meeting = $(this).parents('.meeting');
        const id = meeting.attr('id')
        const title = meeting.find('td.title span').html()
        const datetime = meeting.find('td.datetime span').html()

        $('[data-lp-modal=reschedule-meeting] input[name=meeting_id]').val(id)
        $('[data-lp-modal=reschedule-meeting] .lp-modal__body span.title').html(title)
        $('[data-lp-modal=reschedule-meeting] .lp-modal__body span.datetime').html(datetime)
      })

      $('.lp-page').on('submit', '.lp-modal form.accept-meeting', function (e) {
        e.preventDefault()

        const form = $(this)
        const submit_btn = $(this).find('button[type=submit]')
        const loader = submit_btn.next('.lp-loader')

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: form.serialize(),
          dataType: 'json',
          beforeSend: function () {
            submit_btn.prop('disabled', true)
            loader.removeClass('lp-hide')
          },
          error: function () {
            submit_btn.prop('disabled', false)
            loader.addClass('lp-hide')

            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            submit_btn.prop('disabled', false)
            loader.addClass('lp-hide')

            if (response.success) {
              acceptModal.handleClose()
              $('#data-container #' + response.data.id + ' .meeting-status').attr('title', response.data.status)
              $('#data-container #' + response.data.id + ' .meeting-status').html(response.data.status)
              /* reschedule or invitation */
              if (form.hasClass('reschedule')) {
                new Snack({ message: 'Встреча успешно перенесена.', variant: 'success' }).show()
                form.removeClass('reschedule')
              } else {
                new Snack({ message: 'Приглашение на встречу принято.', variant: 'success' }).show()
              }
            } else {
              if (response.data.hasOwnProperty('message')) {
                new Snack({ message: response.data.message, variant: 'danger' }).show()
              } else {
                new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
              }
            }
          }
        })
      })

      $('.lp-page').on('submit', '.lp-modal form.reject-meeting', function (e) {
        e.preventDefault()

        const form = $(this)
        const submit_btn = form.find('button[type=submit]')
        const loader = submit_btn.next('.lp-loader')

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: form.serialize(),
          dataType: 'json',
          beforeSend: function () {
            submit_btn.prop('disabled', true)
            loader.removeClass('lp-hide')
          },
          error: function () {
            submit_btn.prop('disabled', false)
            loader.addClass('lp-hide')

            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            submit_btn.prop('disabled', false)
            loader.addClass('lp-hide')

            if (response.success) {
              rejectModal.handleClose()
              $('#data-container #' + response.data.id + ' .meeting-status').attr('title', response.data.status)
              $('#data-container #' + response.data.id + ' .meeting-status').html(response.data.status)
              /* display notification */
              new Snack({ message: 'Приглашение на встречу отклонено.', variant: 'success' }).show()
            } else {
              if (response.data.hasOwnProperty('message')) {
                new Snack({ message: response.data.message, variant: 'danger' }).show()
              } else {
                new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
              }
            }
          }
        })
      })
      
      $('.lp-page').on('submit', '.lp-modal form.cancel-meeting', function (e) {
        e.preventDefault()

        const form = $(this)
        const submit_btn = $(this).find('button[type=submit]')
        const loader = submit_btn.next('.lp-loader')

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: form.serialize(),
          dataType: 'json',
          beforeSend: function () {
            submit_btn.prop('disabled', true)
            loader.removeClass('lp-hide')
          },
          error: function () {
            submit_btn.prop('disabled', false)
            loader.addClass('lp-hide')

            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            submit_btn.prop('disabled', false)
            loader.addClass('lp-hide')

            if (response.success) {
              cancelModal.handleClose()
              $('#data-container #' + response.data.id + ' .meeting-status').attr('title', response.data.status)
              $('#data-container #' + response.data.id + ' .meeting-status').html(response.data.status)
              /* display notification */
              new Snack({ message: 'Встреча успешно отменена.', variant: 'success' }).show()
            } else {
              if (response.data.hasOwnProperty('message')) {
                new Snack({ message: response.data.message, variant: 'danger' }).show()
              } else {
                new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
              }
            }
          }
        })
      })
      
      $('.lp-page').on('submit', '.lp-modal form.accept-reschedule-meeting', function (e) {
        e.preventDefault()

        const form = $(this)
        const submit_btn = $(this).find('button[type=submit]')
        const loader = submit_btn.next('.lp-loader')

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: form.serialize(),
          dataType: 'json',
          beforeSend: function () {
            submit_btn.prop('disabled', true)
            loader.removeClass('lp-hide')
          },
          error: function () {
            submit_btn.prop('disabled', false)
            loader.addClass('lp-hide')

            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            submit_btn.prop('disabled', false)
            loader.addClass('lp-hide')

            if (response.success) {
              acceptRescheduleModal.handleClose()
              $('#data-container #' + response.data.id + ' .meeting-status').attr('title', response.data.status)
              $('#data-container #' + response.data.id + ' .meeting-status').html(response.data.status)
              /* display notification */
              new Snack({ message: 'Перенос встречи успешно принят. Дата и время встречи изменены.', variant: 'success' }).show()
            } else {
              if (response.data.hasOwnProperty('message')) {
                new Snack({ message: response.data.message, variant: 'danger' }).show()
              } else {
                new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
              }
            }
          }
        })
      })
      
      $('.lp-page').on('submit', '.lp-modal form.reject-reschedule-meeting', function (e) {
        e.preventDefault()

        const form = $(this)
        const submit_btn = $(this).find('button[type=submit]')
        const loader = submit_btn.next('.lp-loader')

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: form.serialize(),
          dataType: 'json',
          beforeSend: function () {
            submit_btn.prop('disabled', true)
            loader.removeClass('lp-hide')
          },
          error: function () {
            submit_btn.prop('disabled', false)
            loader.addClass('lp-hide')

            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            submit_btn.prop('disabled', false)
            loader.addClass('lp-hide')

            if (response.success) {
              cancelModal.handleClose()
              $('#data-container #' + response.data.id + ' .meeting-status').attr('title', response.data.status)
              $('#data-container #' + response.data.id + ' .meeting-status').html(response.data.status)
              /* display notification */
              new Snack({ message: 'Перенос встречи отклонён.', variant: 'success' }).show()
            } else {
              if (response.data.hasOwnProperty('message')) {
                new Snack({ message: response.data.message, variant: 'danger' }).show()
              } else {
                new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
              }
            }
          }
        })
      })
    }

    if (path.includes('schedule-meeting')) {
      $('.lp-page .lp-textfield').each(function () {
        const pass = ['datetime']
        if (!pass.includes($(this).attr('id'))) {
          new TextField({ selector: $lp(this) })
        }
      })

      const datetime = new TextField({ selector: $lp('#datetime') })

      $('[data-lp-modal]#date-picker').on('change', 'input[type=time]', function () {
        const isDate = (date) => {
          return (new Date(date) !== "Invalid Date") && !isNaN(new Date(date));
        }
        const val = datetime.value;
        if ( isDate( val ) ) {
          if ( val.search(/\d{1,2}:\d{2}/) !== -1 ) {
            datetime.value = val.replace(/\d{1,2}:\d{2}/, $(this).val())
          } else {
            datetime.value = `${datetime.value} ${$(this).val()}`;
          }
          $(datetime.input.nativeElement).valid()
        }
      });

      const date = new Date()
      date.setDate(date.getDate() - 1)
      const datePicker = new DatePicker({
        selector: $lp('[data-lp-modal]#date-picker #datepicker'),
        options: {
          minDate: date,
          maxDate: new Date('31.12.2022')
        }
      })
      datePicker.onSelect = (day) => {
        const formatter = new Intl.DateTimeFormat('ru')
        let date = formatter.format(day.data.Date)
        if (day.time !== null) {
          date += ` ${day.time}`
        }
        datetime.value = date
        $(datetime.input.nativeElement).valid()
      }

      const datepickerModal = new Modal({ selector: $lp('[data-lp-modal]#date-picker') })

      $('#datetime').on('click', () => {
        datepickerModal.handleOpen()
      })

      $('.lp-page').on('click', '[data-lp-modal]#date-picker button[data-lp-modal=accept]', () => {
        datepickerModal.handleClose()
      })

      jQuery.extend(jQuery.validator.messages, {
        required: 'Это поле обязательно к заполнению'
      })

      jQuery.validator.addMethod('validateDateTime', function (value, element) {
        return this.optional(element) || /^(0[1-9]|([12][0-9])|(3[01])).([0]{0,1}[1-9]|1[012]).([012][0-9][0-9][0-9]) [0-2][0-9]:[0-5][0-9]/.test(value)
      }, 'Введите дату и время в таком формате DD.MM.YYY HH:MM.')

      $('.lp-page form').validate({
        ignore: [],
        rules: {
          invitor: {
            required: true,
            maxlength: 50
          },
          invited: {
            required: true,
            maxlength: 50
          },
          title: {
            required: true,
            maxlength: 100
          },
          venue: {
            required: true,
            maxlength: 100
          },
          datetime: {
            required: true,
            validateDateTime: true
          },
          format: 'required',
          description: {
            required: true,
            maxlength: 1000
          }
        },
        errorClass: 'lp-invalid',
        errorElement: 'span',
        validClass: 'lp-valid',
        messages: {
          invitor: {
            maxlength: 'Имя приглашающего не должно быть длинее 50 символов.'
          },
          invited: {
            maxlength: 'Имя приглашаемого не должно быть длинее 50 символов.'
          },
          format: {
            required: 'Выберите формат встречи.'
          },
          title: {
            maxlength: 'Заголовок встречи не должен быть длинее 100 символов.'
          },
          venue: {
            maxlength: 'Указание места встречи не должно быть длинее 100 символов.'
          },
          description: {
            maxlength: 'Описание встречи не должно быть длинее 1000.'
          }
        },
        errorPlacement: function (error, element) {
          if ($(element).attr('name') === 'format') {
            error.insertAfter($(element).parents('.radio-group'))
          }
          error.insertAfter($(element).parents('.lp-textfield'))
        },
        highlight: function (element, errorClass, validClass) {
          $(element).parents('.lp-textfield').addClass(errorClass).removeClass(validClass)
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).parents('.lp-textfield').removeClass(errorClass).addClass(validClass)
        },
        submitHandler: function (form) {
          const submit_btn = $(form).find('button[type=submit]')
          const loader = submit_btn.next('.lp-loader')

          $.ajax({
            type: 'post',
            url: ajaxurl,
            data: new FormData(form),
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function () {
              submit_btn.prop('disabled', true)
              loader.removeClass('lp-hide')
            },
            error: function (xhr, ajaxOptions, thrownError) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')

              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            },
            success: function (response) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')

              if (response.success) {
                new Snack({ message: 'Приглашение на встречу успешно отправлено.', variant: 'success' }).show()
                window.location.href = profile_chat_url
              } else {
                if (response.data.hasOwnProperty('message')) {
                  new Snack({ message: response.data.message, variant: 'success' }).show()
                } else {
                  new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
                }
              }
            }
          })

          return false
        }
      })
    }

    if (path.includes('/profile/meetings/calendar/')) {
      const calendar = new Calendar({
        selector: $lp('.lp-calendar'),
        dayTemplate: (data) => {
          for (const [key, day] of Object.entries(month_meetings)) {
            if (data.date == key) {
              let meetings = ''
              for (const [id, info] of Object.entries(day)) {
                meetings += `<button class="meeting-time lp-button-base lp-button lp-theme-primary lp-variant-filled" data-id="${id}" title="${info.title}"><span>${info.time}</span><span>${info.title}</span></button>`
              }
              return meetings
            }
          }
          return '<span></span>'
        }
      })

      /* meeting info */
      const invited = new TextField({ selector: $lp('[data-lp-modal=view-meeting] .invited') })
      const venue = new TextField({ selector: $lp('[data-lp-modal=view-meeting] .venue') })
      const desc = new TextField({ selector: $lp('[data-lp-modal=view-meeting] .description') })

      const dayModal = new Modal({ selector: $lp('[data-lp-modal=view-meeting]') })

      const datetime = new TextField({ selector: $lp('#datetime') })
      const date = new Date()
      date.setDate(date.getDate() - 1)
      const datePicker = new DatePicker({
        selector: $lp('[data-lp-modal]#date-picker #datepicker'),
        options: {
          minDate: date,
          maxDate: new Date('31.12.2022')
        }
      })
      const datepickerModal = new Modal({ selector: $lp('[data-lp-modal]#date-picker') })

      datepickerModal.onClose = function () {
        datetime.value = ''
      }

      $('#datetime').on('click', () => {
        datepickerModal.handleOpen()

        const input_field = $(this).find('input[name=datetime]')

        datePicker.onSelect = (day) => {
          const formatter = new Intl.DateTimeFormat('ru')
          // console.log(day);
          let date = formatter.format(day.data.Date)
          if (day.time !== null) {
            date += ` ${day.time}`
          }
          datetime.value = date
          input_field.valid()
        }
      })

      $('[data-lp-modal=reschedule-meeting] form').validate({
        ignore: [],
        rules: {
          datetime: 'required'
        },
        errorClass: 'lp-invalid',
        errorElement: 'span',
        validClass: 'lp-valid',
        messages: {
          datetime: {
            required: 'Укажите новые дату и время встречи'
          }
        },
        errorPlacement: function (error, element) {
          error.insertAfter($(element).parents('.lp-textfield'))
        },
        highlight: function (element, errorClass, validClass) {
          $(element).parents('.lp-textfield').addClass(errorClass).removeClass(validClass)
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).parents('.lp-textfield').removeClass(errorClass).addClass(validClass)
        },
        submitHandler: function (form) {
          const submit_btn = $(form).find('button[type=submit]')
          loader = submit_btn.next('.lp-loader')

          $.ajax({
            type: 'post',
            url: ajaxurl,
            data: $(form).serialize(),
            dataType: 'json',
            beforeSend: function () {
              submit_btn.prop('disabled', true)
              loader.removeClass('lp-hide')
            },
            error: function (xhr, ajaxOptions, thrownError) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            },
            success: function (response) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')

              if (response.success) {
                rescheduleModal.handleClose()
                dayModal.handleClose()
                new Snack({ message: 'Предложение о переносе встречи отправлено второму учаснику.', variant: 'success' }).show()
              } else {
                if (response.data.hasOwnProperty('message')) {
                  new Snack({ message: response.data.message, variant: 'danger' }).show()
                } else {
                  new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
                }
              }
            }
          })

          return false
        }
      })

      $('[data-lp-modal=cancel-meeting] form').on('submit', function (e) {
        e.preventDefault()

        const form = $(this)
        const submit_btn = form.find('button[type=submit]')
        const loader = submit_btn.next('.lp-loader')

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: form.serialize(),
          dataType: 'json',
          beforeSend: function () {
            submit_btn.prop('disabled', true)
            loader.removeClass('lp-hide')
          },
          error: function (xhr, ajaxOptions, thrownError) {
            submit_btn.prop('disabled', false)
            loader.removeClass('lp-hide')
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            if (response.success) {
              cancelModal.handleClose()
              dayModal.handleClose()
              new Snack({ message: 'Встреча отменена.', variant: 'success' }).show()
            } else {
              if (response.data.hasOwnProperty('message')) {
                new Snack({ message: response.data.message, variant: 'danger' }).show()
              } else {
                new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
              }
            }
          }
        })
      })

      const cancelModal = new Modal({ selector: $lp('[data-lp-modal=cancel-meeting]') })
      const rescheduleModal = new Modal({ selector: $lp('[data-lp-modal=reschedule-meeting]') })

      $('.lp-calendar').on('click', 'button.meeting-time', function () {
        const btn = $(this)
        const id = $(this).data('id')

        $.ajax({
          type: 'get',
          url: ajaxurl,
          data: {
            action: 'dao-get-meeting-info',
            id: id,
            _wpnonce: get_meetinginfo_nonce
          },
          dataType: 'json',
          beforeSend: function () {
            btn.prop('disabled', true)
          },
          error: function () {
            btn.prop('disabled', false)
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            btn.prop('disabled', false)
            if (response.success) {
              dayModal.handleOpen()
              const modal = $('[data-lp-modal=view-meeting]')
              modal.data('id', response.data.id)
              modal.find('.title').text(response.data.title)
              modal.find('.time').text(response.data.time)
              modal.find('.date').text(response.data.date)
              modal.find('.format').text(response.data.format)
              modal.find('.status').text(response.data.status)
              invited.value = response.data.invited
              venue.value = response.data.venue
              desc.value = response.data.desc
              modal.find('.lp-modal__footer .lp-container').empty()
              if (response.data.author) {
                modal.find('.lp-modal__footer .lp-container').append('<div class="lp-grid lp-item"> <button class="cancel-meeting lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-outlined" data-lp-modal="cancel"> Отменить </button> </div> <div class="lp-grid lp-item"> <button class="reschedule-meeting lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled">Перенести</button> </div>')
              } else {
                modal.find('.lp-modal__footer .lp-container').append('<div class="lp-grid lp-item"> <button class="reschedule-meeting lp-button-base lp-button lp-size-small lp-theme-primary lp-variant-filled"> Перенести </button> </div>')
              }
            } else {
              if (response.data.hasOwnProperty('message')) {
                new Snack({ message: response.data.message, variant: 'danger' }).show()
              } else {
                new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
              }
            }
          }
        })
      })

      calendar.onChangeMonth = (month) => {
        $.ajax({
          type: 'get',
          url: ajaxurl,
          async: false,
          data: {
            action: 'dao-get-meetings-for-month',
            month: month.number,
            year: month.year,
            _wpnonce: get_meetings_month_nonce
          },
          dataType: 'json',
          error: function (xhr, ajaxOptions, thrownError) {
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            if (response.success) {
              month_meetings = response.data.meetings
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      }

      $(document).on('click', '[data-lp-modal=view-meeting] .lp-modal__footer .cancel-meeting', function () {
        cancelModal.handleOpen()

        const id = $('[data-lp-modal=view-meeting]').data('id')
        const title = $('[data-lp-modal=view-meeting] h5.title').html()
        const date = $('[data-lp-modal=view-meeting] h5.date').html()
        const time = $('[data-lp-modal=view-meeting] h4.time').html()

        $('[data-lp-modal=cancel-meeting] input[name=meeting_id]').val(id)
        $('[data-lp-modal=cancel-meeting] .lp-modal__body span.title').html(title)
        $('[data-lp-modal=cancel-meeting] .lp-modal__body span.datetime').html(`${date} ${time}`)
      })

      $(document).on('click', '[data-lp-modal=view-meeting] .lp-modal__footer .reschedule-meeting', function () {
        rescheduleModal.handleOpen()

        const id = $('[data-lp-modal=view-meeting]').data('id')
        const title = $('[data-lp-modal=view-meeting] h5.title').html()
        const date = $('[data-lp-modal=view-meeting] h5.date').html()
        const time = $('[data-lp-modal=view-meeting] h4.time').html()

        $('[data-lp-modal=reschedule-meeting] input[name=meeting_id]').val(id)
        $('[data-lp-modal=reschedule-meeting] .lp-modal__body span.title').html(title)
        $('[data-lp-modal=reschedule-meeting] .lp-modal__body span.datetime').html(`${date} ${time}`)
      })
    }

    if (path === '/profile/change-userdata/') {
      $('.lp-textfield').each(function () {
        new TextField({ selector: $lp(this) })
      })
      $('.lp-select').each(function () {
        new Select({ selector: $lp(this), isMulti: false, searchable: true })
      })

      /* init Select2 */
      $('select.skills').select2({
        width: '100%',
        maximumSelectionLength: 20,
        placeholder: 'Выберите навыки',
        allowClear: true,
        tags: true
      })

      /* change counter of available skills */
      const count = $('select.skills :selected').length
      $('.available-skills #counter').text(20 - count)

      $('select.skills').on('change', function () {
        const counter = $(this).parent().find('.select2-selection__choice').length
        $(this).parent().find('.available-skills #counter').text(20 - counter)
      })

      $('.lp-radio input[name=user_type]').on('change', function () {
        if ($(this).val() === 'juridical') {
          $('.company-info').removeClass('lp-hide')
          $('.company-info input').each(function () {
            $(this).val('')
            $(this).trigger('change')
          })
          $('form.change-userdata-form input[name=company_email]').rules('add', {
            email: true,
            messages: {
              email: 'Введите email в таком формате user@domain.tld'
            }
          })
        } else {
          $('.company-info').addClass('lp-hide')
          $('form.change-userdata-form input[name=company_email]').rules('remove')
        }
      })

      /* profile picture */
      $('form.change-userdata-form input[name=profile_picture]').on('change', function () {
        if ($(this).get(0).files.length !== 0) {
          $(this).valid()

          const reader = new FileReader()
          const picture = $(this).get(0).files[0]

          reader.onload = function (e) {
            $('.lp-profile-picture img').attr('src', e.target.result)
          }

          reader.readAsDataURL(picture)
        }
      })

      jQuery.extend(jQuery.validator.messages, {
        required: 'Это поле обязательно к заполнению'
      })

      $('form.change-userdata-form').validate({
        ignore: [],
        rules: {
          profile_picture: {
            accept: 'image/jpeg,image/jpg,image/png',
            maxsize: 3145728
          },
          first_name: 'required',
          last_name: 'required',
          email: {
            required: true,
            email: true
          },
          description: 'required',
          person_type: 'required',
          'skills[]': 'required'
        },
        errorClass: 'lp-invalid',
        errorElement: 'span',
        validClass: 'lp-valid',
        messages: {
          profile_picture: {
            accept: 'Изображение профиля должно быть в формате jpg, jpeg или png',
            maxsize: 'Изображение профиля должно весить не больше 3 МВ'
          },
          'skills[]': {
            required: 'Выберите хотя бы один навык, которым вы владеете'
          }
        },
        errorPlacement: function (error, element) {
          if ($(element).hasClass('select2')) {
            error.insertAfter($(element).next())
            return
          }
          if ($(element).attr('name') === 'profile_picture') {
            error.insertAfter($(element).parents('.lp-upload-profile-picture'))
            return
          }
          error.insertAfter($(element).parents('.lp-textfield'))
        },
        highlight: function (element, errorClass, validClass) {
          if ($(element).hasClass('select2')) {
            $(element).addClass(errorClass).removeClass(validClass)
            return
          }
          if ($(element).attr('name') === 'profile_picture') {
            $(element).parents('.lp-upload-profile-picture').addClass(errorClass).removeClass(validClass)
            return
          }
          $(element).parents('.lp-textfield').addClass(errorClass).removeClass(validClass)
        },
        unhighlight: function (element, errorClass, validClass) {
          if ($(element).hasClass('select2')) {
            $(element).removeClass(errorClass).addClass(validClass)
            return
          }
          if ($(element).attr('name') === 'profile_picture') {
            $(element).parents('.lp-upload-profile-picture').removeClass(errorClass).addClass(validClass)
            return
          }
          $(element).parents('.lp-textfield').removeClass(errorClass).addClass(validClass)
        },
        submitHandler: function (form) {
          const submit_btn = $(form).find('button[type=submit]')
          const loader = submit_btn.next('.lp-loader')

          let formData = new FormData()

          if ($('.lp-radio input[name=user_type]:checked').val() !== 'juridical') {
            $(form).find('input[type=text], input[type=file], input[type=hidden], input[type=radio]:checked, textarea:not(.select2-search__field), select').each(function () {
              const name = $(this).attr('name')
              const not_allowed = ['company_name', 'company_workarea', 'company_juraddress', 'company_phone', 'company_email', 'company_website']

              if (!not_allowed.includes(name)) {
                if (name === 'profile_picture') {
                  formData.append(name, $(this).get(0).files[0])
                } else {
                  formData.append(name, $(this).val())
                }
              }
            })
          } else {
            formData = new FormData(form)
          }

          $.ajax({
            type: 'post',
            url: ajaxurl,
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function () {
              submit_btn.prop('disabled', true)
              loader.removeClass('lp-hide')
            },
            error: function (xhr, ajaxOptions, thrownError) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')

              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            },
            success: function (response) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')

              if (response.success) {
                new Snack({ message: 'Данные вашего профиля успешно изменены.', variant: 'success' }).show()
                // location.reload();
              } else {
                if (response.data.hasOwnProperty('message')) {
                  new Snack({ message: response.data.message, variant: 'danger' }).show()
                } else {
                  new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
                }
              }
            }
          })

          return false
        }
      })

      $('form.change-password-form').validate({
        ignore: [],
        rules: {
          old_password: 'required',
          new_password: 'required',
          repeat_new_password: 'required'
        },
        errorClass: 'lp-invalid',
        errorElement: 'span',
        validClass: 'lp-valid',
        errorPlacement: function (error, element) {
          error.insertAfter($(element).parents('.lp-textfield'))
        },
        highlight: function (element, errorClass, validClass) {
          $(element).parents('.lp-textfield').addClass(errorClass).removeClass(validClass)
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).parents('.lp-textfield').removeClass(errorClass).addClass(validClass)
        },
        submitHandler: function (form) {
          const submit_btn = $(form).find('button[type=submit]')
          const loader = submit_btn.next('.lp-loader')

          $.ajax({
            type: 'post',
            url: ajaxurl,
            data: new FormData(form),
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function () {
              submit_btn.prop('disabled', true)
              loader.removeClass('lp-hide')
            },
            error: function (xhr, ajaxOptions, thrownError) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')

              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            },
            success: function (response) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')

              if (response.success) {
                new Snack({ message: 'Ваш пароль успешно изменён.', variant: 'success' }).show()
                window.location.href = login_url
              } else {
                if (response.data.hasOwnProperty('message')) {
                  new Snack({ message: response.data.message, variant: 'danger' }).show()
                } else {
                  new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
                }
              }
            }
          })

          return false
        }
      })

      $('form.change-password-form button.show-pass').on('click', function () {
        const input = $(this).parents('.lp-textfield__input').find('input')
        const icon = $(this).find('i')

        if (input.attr('type') === 'password') {
          input.attr('type', 'text')
          icon.removeClass('lp-eye-flat').addClass('lp-eye-slash-flat')
        } else {
          input.attr('type', 'password')
          icon.removeClass('lp-eye-slash-flat').addClass('lp-eye-flat')
        }
      })

      const changePasswordModal = new Modal({ selector: $lp('[data-lp-modal]#change-password') })
      $('#change-password').on('click', function () {
        changePasswordModal.handleOpen()
      })
    }

    if (path === '/profile/chat/') {

    }

    if (path === '/profile/notifications/') {
      $('.lp-notifications').on('click', '.lp-notification.lp-after-meeting .lp-options button', function (e) {
        const result = $(this).data('result')
        $(this).parents('.lp-notification').find('input[name=result]').val(result)
      })

      $('.lp-notifications').on('submit', '.lp-notification.lp-after-meeting .lp-options form', function (e) {
        e.preventDefault()

        const form = $(this)
        const notification = $(this).parents('.lp-notification')
        const submit_btns = form.find('button[type=submit]')
        const loader = form.parents('.lp-notification').find('.lp-loader')

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: form.serialize(),
          dataType: 'json',
          beforeSend: function () {
            submit_btns.prop('disabled', true)
            loader.removeClass('lp-hide')
          },
          error: function (xhr, ajaxOptions, thrownError) {
            submit_btns.prop('disabled', false)
            loader.addClass('lp-hide')
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            submit_btns.prop('disabled', false)
            loader.addClass('lp-hide')
            if (response.success) {
              notification.remove()
              if (response.data.result === 'success') {
                if (response.data.type === 'demand') {
                  new Snack({ message: `Статус спроса "${response.data.title}" изменён на "В работе". Исполнитель: ${response.data.performer}.`, variant: 'success' }).show()
                } else {
                  new Snack({ message: `Сделка по предложению ${response.data.performer} "${response.data.title}" заключена.`, variant: 'success' }).show()
                }
              } else {
                if (response.data.type === 'demand') {
                  new Snack({ message: `Вы отказались от сотрудничества с ${response.data.performer} по спросу "${response.data.title}".`, variant: 'success' }).show()
                } else {
                  new Snack({ message: `Вы отказались от предложения ${response.data.performer} "${response.data.title}".`, variant: 'success' }).show()
                }
              }
            } else {
              if (response.data.hasOwnProperty('message')) {
                new Snack({ message: response.data.message, variant: 'danger' }).show()
              } else {
                new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
              }
            }
          }
        })
      })

      let options = {
        totalItems: found_notifications,
        itemsPerPage: query_vars.per_page,
        visiblePages: 4,
        page: 1,
        centerAlign: true,
        usageStatistics: false,
        firstItemClassName: 'tui-first-child',
        lastItemClassName: 'tui-last-child',
        template: {
          page: '<a href="#" class="btn-page lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">{{page}}</a>',
          currentPage: '<strong class="lp-button-base lp-button-nav lp-size-medium lp-theme-primary lp-variant-filled">{{page}}</strong>',
          moveButton:
                    '<a href="#" class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled lp-nav-{{type}}">' +
                        '<span class="tui-ico-{{type}}">{{type}}</span>' +
                    '</a>',
          disabledMoveButton:
                    '<span class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat tui-is-disabled lp-nav-{{type}}">' +
                        '<span class="tui-ico-{{type}}">{{type}}</span>' +
                    '</span>',
          moreButton:
                    '<a href="#" class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">' +
                        '<span class="tui-ico-ellip">...</span>' +
                    '</a>'
        }
      }

      if (max_num_pages > 2) {
        const pagination = new tui.Pagination('pagination', options)

        pagination.on('afterMove', (event) => {
          query_vars.page = event.page

          $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
              action: action,
              query_vars: query_vars,
              _wpnonce: nonce
            },
            dataType: 'json',
            error: function (xhr, ajaxOptions, thrownError) {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            },
            success: function (response) {
              if (response.success) {
                $('.lp-notifications').html(response.data.notifications)
                mark_unseen_notifications()
              } else {
                new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
              }
            }
          })
        })
      }

      mark_unseen_notifications()

      function mark_unseen_notifications () {
        const ids = []
        $('.lp-notifications .lp-notification.lp-unseen').each(function () {
          ids.push($(this).attr('id'))
        })
        // mark unseen notifications as seen
        if (ids.length !== 0) {
          $.post(ajaxurl, { action: 'dao-mark-unseen-notifications', ids: ids, _wpnonce: mark_unseen_not_nonce }, function (response) {
          })
        }
      }
    }

    if (path === '/profile/submit-testimonial/') {
      $('.lp-textfield').each(function () {
        new TextField({ selector: $lp(this) })
      })

      $('.lp-submit-testimonial-page .rating-scale').each(function () {
        let options = {
          max_value: 5,
          step_size: 0.5
        }
        $(this).rate(options)
        $(this).on('change', function (ev, data) {
          $(ev.target).next('input[type=hidden]').val(data.to)
          $(ev.target).next('input[type=hidden]').valid()
        })
      })

      $('.lp-submit-testimonial-page form').validate({
        ignore: [],
        rules: {
          quality: 'required',
          professionality: 'required',
          cost: 'required',
          sociability: 'required',
          deadline: 'required',
          body: 'required'
        },
        errorClass: 'lp-invalid',
        errorElement: 'span',
        validClass: 'lp-valid',
        messages: {
          quality: {
            required: 'Обязательно к заполнению'
          },
          professionality: {
            required: 'Обязательно к заполнению'
          },
          cost: {
            required: 'Обязательно к заполнению'
          },
          sociability: {
            required: 'Обязательно к заполнению'
          },
          deadline: {
            required: 'Обязательно к заполнению'
          },
          body: {
            required: 'Это поле обязательно к заполнению'
          }
        },
        errorPlacement: function (error, element) {
          if ($(element).hasClass('grade')) {
            error.insertAfter($(element).parents('.lp-rating'))
            return
          }
          error.insertAfter($(element).parents('.lp-textfield'))
        },
        highlight: function (element, errorClass, validClass) {
          if ($(element).hasClass('grade')) {
            $(element).parents('.lp-rating').addClass(errorClass).removeClass(validClass)
            return
          }
          $(element).parents('.lp-textfield').addClass(errorClass).removeClass(validClass)
        },
        unhighlight: function (element, errorClass, validClass) {
          if ($(element).hasClass('grade')) {
            $(element).parents('.lp-rating').removeClass(errorClass).addClass(validClass)
            return
          }
          $(element).parents('.lp-textfield').removeClass(errorClass).addClass(validClass)
        },
        submitHandler: function (form) {
          const submit_btn = $(form).find('button[type=submit]')
          const loader = submit_btn.next('.lp-loader')

          $.ajax({
            type: 'post',
            url: ajaxurl,
            data: $(form).serialize(),
            dataType: 'json',
            beforeSend: function () {
              submit_btn.prop('disabled', true)
              loader.removeClass('lp-hide')
            },
            error: function (xhr, ajaxOptions, thrownError) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')

              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            },
            success: function (response) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')

              if (response.success) {
                new Snack({ message: 'Ваш отзыв успешно отправлен.', variant: 'success' }).show()
                setTimeout(function () {
                  window.location.href = profile_url
                }, 2000)
              } else {
                if (response.data.hasOwnProperty('message')) {
                  new Snack({ message: response.data.message, variant: 'danger' }).show()
                } else {
                  new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
                }
              }
            }
          })

          return false
        }
      })
    }

    if (path.includes('/profile/rating/')) {
      /* initialize pagination */
      let options = {
        totalItems: found_users,
        itemsPerPage: query_vars.number,
        visiblePages: 4,
        page: 1,
        centerAlign: true,
        usageStatistics: false,
        firstItemClassName: 'tui-first-child',
        lastItemClassName: 'tui-last-child',
        template: {
          page: '<a href="#" class="btn-page lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">{{page}}</a>',
          currentPage: '<strong class="lp-button-base lp-button-nav lp-size-medium lp-theme-primary lp-variant-filled">{{page}}</strong>',
          moveButton:
                    '<a href="#" class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled lp-nav-{{type}}">' +
                        '<span class="tui-ico-{{type}}">{{type}}</span>' +
                    '</a>',
          disabledMoveButton:
                    '<span class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat tui-is-disabled lp-nav-{{type}}">' +
                        '<span class="tui-ico-{{type}}">{{type}}</span>' +
                    '</span>',
          moreButton:
                    '<a href="#" class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">' +
                        '<span class="tui-ico-ellip">...</span>' +
                    '</a>'
        }
      }

      const pagination = new tui.Pagination('pagination', options)

      pagination.on('afterMove', (event) => {
        query_vars.paged = event.page

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: {
            action: action,
            query_vars: query_vars,
            _wpnonce: nonce
          },
          dataType: 'json',
          error: function (xhr, ajaxOptions, thrownError) {
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            if (response.success) {
              $('tbody#data-container').html(response.data.rows)
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      })

      if (max_num_pages < 2) {
        $('#pagination').hide()
      }

      $('.lp-table-rating thead #rating').on('click', function () {
        const icon = $(this).children('.lp-icon')

        if (icon.hasClass('lp-triangle-filter')) icon.removeClass('lp-triangle-filter')

        if (query_vars.orderby.hasOwnProperty('meetings')) {
          delete query_vars.orderby.meetings
          delete query_vars.meta_query.meetings
        }

        if (query_vars.orderby.hasOwnProperty('transactions')) {
          delete query_vars.orderby.transactions
          delete query_vars.meta_query.transactions
        }

        if (!query_vars.meta_query.hasOwnProperty('rating')) {
          query_vars.meta_query.rating = {}
          query_vars.meta_query.rating.key = 'rating'
          query_vars.meta_query.rating.compare = 'EXISTS'
        }

        $('.lp-table-rating thead #meetings').find('i.lp-icon').attr('class', 'lp-icon lp-postfix lp-triangle-filter')
        $('.lp-table-rating thead #transactions').find('i.lp-icon').attr('class', 'lp-icon lp-postfix lp-triangle-filter')

        if (icon.hasClass('lp-triangle-up')) {
          icon.removeClass('lp-triangle-up').addClass('lp-triangle-down')
          query_vars.orderby.rating = 'DESC'
        } else {
          icon.removeClass('lp-triangle-down').addClass('lp-triangle-up')
          query_vars.orderby.rating = 'ASC'
        }

        get_users()
      })

      $('.lp-table-rating thead #meetings').on('click', function () {
        const icon = $(this).children('.lp-icon')

        if (icon.hasClass('lp-triangle-filter')) icon.removeClass('lp-triangle-filter')

        if (query_vars.orderby.hasOwnProperty('rating')) {
          delete query_vars.orderby.rating
          delete query_vars.meta_query.rating
        }

        if (query_vars.orderby.hasOwnProperty('transactions')) {
          delete query_vars.orderby.transactions
          delete query_vars.meta_query.transactions
        }

        if (!query_vars.meta_query.hasOwnProperty('meetings')) {
          query_vars.meta_query.meetings = {}
          query_vars.meta_query.meetings.key = 'completed_meetings'
          query_vars.meta_query.meetings.compare = 'EXISTS'
        }

        $('.lp-table-rating thead #rating').find('i.lp-icon').attr('class', 'lp-icon lp-postfix lp-triangle-filter')
        $('.lp-table-rating thead #transactions').find('i.lp-icon').attr('class', 'lp-icon lp-postfix lp-triangle-filter')

        if (icon.hasClass('lp-triangle-up')) {
          icon.removeClass('lp-triangle-up').addClass('lp-triangle-down')
          query_vars.orderby.meetings = 'DESC'
        } else {
          icon.removeClass('lp-triangle-down').addClass('lp-triangle-up')
          query_vars.orderby.meetings = 'ASC'
        }

        get_users()
      })

      $('.lp-table-rating thead #transactions').on('click', function () {
        const icon = $(this).children('.lp-icon')

        if (icon.hasClass('lp-triangle-filter')) icon.removeClass('lp-triangle-filter')

        if (query_vars.orderby.hasOwnProperty('rating')) {
          delete query_vars.orderby.rating
          delete query_vars.meta_query.rating
        }

        if (query_vars.orderby.hasOwnProperty('meetings')) {
          delete query_vars.orderby.meetings
          delete query_vars.meta_query.meetings
        }

        if (!query_vars.meta_query.hasOwnProperty('transactions')) {
          query_vars.meta_query.transactions = {}
          query_vars.meta_query.transactions.key = 'completed_transactions'
          query_vars.meta_query.transactions.compare = 'EXISTS'
        }

        $('.lp-table-rating thead #rating').find('i.lp-icon').attr('class', 'lp-icon lp-postfix lp-triangle-filter')
        $('.lp-table-rating thead #meetings').find('i.lp-icon').attr('class', 'lp-icon lp-postfix lp-triangle-filter')

        if (icon.hasClass('lp-triangle-up')) {
          icon.removeClass('lp-triangle-up').addClass('lp-triangle-down')
          query_vars.orderby.transactions = 'DESC'
        } else {
          icon.removeClass('lp-triangle-down').addClass('lp-triangle-up')
          query_vars.orderby.transactions = 'ASC'
        }

        get_users()
      })

      function get_users () {
        query_vars.paged = 1

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: {
            action: action,
            query_vars: query_vars,
            _wpnonce: nonce
          },
          dataType: 'json',
          error: function (xhr, ajaxOptions, thrownError) {
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            if (response.success) {
              $('.lp-table-rating #data-container').html(response.data.rows)
              if (max_num_pages >= 2) {
                pagination.reset(found_users)
              }
            } else {
              if (response.data.hasOwnProperty('message')) {
                new Snack({ message: response.data.message, variant: 'danger' }).show()
              } else {
                new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
              }
            }
          }
        })
      }
    }

    if (path.includes('/profile/testimonials/')) {
      if ( vars.found !== 0 ) {
        let options = {
          totalItems: vars.found,
          itemsPerPage: vars.per_page,
          visiblePages: 4,
          page: 1,
          centerAlign: true,
          usageStatistics: false,
          firstItemClassName: 'tui-first-child',
          lastItemClassName: 'tui-last-child',
          template: {
            page: '<a href="#" class="btn-page lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">{{page}}</a>',
            currentPage: '<strong class="lp-button-base lp-button-nav lp-size-medium lp-theme-primary lp-variant-filled">{{page}}</strong>',
            moveButton:
                      '<a href="#" class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled lp-nav-{{type}}">' +
                          '<span class="tui-ico-{{type}}">{{type}}</span>' +
                      '</a>',
            disabledMoveButton:
                      '<span class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat tui-is-disabled lp-nav-{{type}}">' +
                          '<span class="tui-ico-{{type}}">{{type}}</span>' +
                      '</span>',
            moreButton:
                      '<a href="#" class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">' +
                          '<span class="tui-ico-ellip">...</span>' +
                      '</a>'
          }
        }
  
        const pagination = new tui.Pagination('pagination', options)
  
        if (vars.max_num_pages < 2) {
          $('#pagination').hide()
        }
  
        pagination.on('afterMove', (event) => {
          vars.page = event.page
  
          $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
              action: get_test_action,
              per_page: vars.per_page,
              page: vars.page,
              _wpnonce: get_test_nonce
            },
            dataType: 'json',
            error: function (xhr, ajaxOptions, thrownError) {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            },
            success: function (response) {
              if (response.success) {
                $('#data-container').html(response.data.testimonials)
              } else {
                new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
              }
            }
          })
        })
      }
    }

    if (path.includes('/favourites/')) {
      if ($('#data-container').length !== 0) {
        $(document).on('submit', '[data-lp-card=delete-from-favourites-modal] form', function (e) {
          e.preventDefault()

          const form = $(this)
          const submit_btn = form.find('button[type=submit]')
          const loader = submit_btn.next('.lp-loader')

          $.ajax({
            type: 'post',
            url: ajaxurl,
            data: form.serialize(),
            dataType: 'json',
            beforeSend: function () {
              submit_btn.prop('disabled', true)
              loader.removeClass('lp-hide')
            },
            error: function (xhr, ajaxOptions, thrownError) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            },
            success: function (response) {
              submit_btn.prop('disabled', false)
              loader.addClass('lp-hide')

              if (response.success) {
                new Snack({ message: `${response.data.type} "${response.data.title}" успешно удалено из Избранное.`, variant: 'success' }).show()
                $('#data-container #' + response.data.id).remove()
              } else {
                if (response.data.hasOwnProperty('message')) {
                  new Snack({ message: response.data.message, variant: 'danger' }).show()
                } else {
                  new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
                }
              }
            }
          })
        })

        $('form.filters .lp-textfield').each(function () {
          new TextField({ selector: $lp(this) })
        })

        const categories = new Select({ selector: $lp('form.filters #categories'), isMulti: true, searchable: true })

        categories.onSelect = (option) => {
          const tag = option[option.length - 1]
          const html = `<div class="tag lp-grid lp-item lp-chip lp-default lp-variant-outlined" data-text="${tag.text}" data-value="${tag.value}"><span class="lp-chip__label">${tag.text}</span> <button type="button" class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded" data-lp-select="clear"> <i class="lp-icon lp-times-flat"></i> </button> </div>`
          $('.selected-categories').append(html)

          get_favourites()
        }

        categories.onDeselect = (option) => {
          $('.selected-categories').empty()
          for (let i = 0; option.length > i; ++i) {
            const html = `<div class="tag lp-grid lp-item lp-chip lp-default lp-variant-outlined" data-text="${option[i].text}" data-value="${option[i].value}"><span class="lp-chip__label">${option[i].text}</span> <button type="button" class="lp-button-base lp-button-icon lp-size-small lp-theme-default lp-variant-flat lp-rounded" data-lp-select="clear"> <i class="lp-icon lp-times-flat"></i> </button> </div>`
            $('.selected-categories').append(html)
          }
          get_favourites()
        }

        categories.onClear = (option) => {
          $('.selected-categories').empty()
          get_favourites()
        }

        $('.selected-categories').on('click', '.tag button', function () {
          const txt = $(this).parent().data('text')
          const val = $(this).parent().data('value')

          const selectedOptions = categories.selectedOptions.filter(function (option, index, arr) {
            return option.value === val
          })

          selectedOptions.forEach((option) => {
            categories.handleSelectOptions(option.id, option.option, option.value)
          })

          /* remove specific text and value */
          const fil_text = categories.value.text().split(', ').filter(function (value, index, arr) {
            return value !== txt
          })
          const fil_vals = categories.textfield.value.split(',').filter(function (value, index, arr) {
            return value !== val && value !== ''
          })

          /* renew text and value */
          categories.value.text(fil_text.join(', '))
          categories.textfield.value = fil_vals.join(',')

          if (fil_vals.length === 0) {
            categories.handleClearSelect()
          }

          $(this).parent().remove()

          get_favourites()
        })

        $('#data-container .lp-card').each(function () {
          new FavouriteCard({ selector: $lp(this) })
        })

        /* Set events on search field and btn filters */
        $('form.filters input[name=search_query]').on('change', function (e) {
          e.preventDefault()

          get_favourites()
        })

        /* prevent from page reload when Enter key is pressed */
        $('form.filters input[name=search_query]').keypress(function (event) {
          if (event.keyCode == 13) {
            event.preventDefault()
          }
        })

        /* initialize pagination */
        let options = {
          totalItems: found_posts,
          itemsPerPage: query_vars.posts_per_page,
          visiblePages: 4,
          page: 1,
          centerAlign: true,
          usageStatistics: false,
          firstItemClassName: 'tui-first-child',
          lastItemClassName: 'tui-last-child',
          template: {
            page: '<a href="#" class="btn-page lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">{{page}}</a>',
            currentPage: '<strong class="lp-button-base lp-button-nav lp-size-medium lp-theme-primary lp-variant-filled">{{page}}</strong>',
            moveButton:
                        '<a href="#" class="lp-button-base lp-button-icon lp-size-medium lp-theme-secondary lp-variant-filled lp-nav-{{type}}">' +
                            '<span class="tui-ico-{{type}}">{{type}}</span>' +
                        '</a>',
            disabledMoveButton:
                        '<span class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat tui-is-disabled lp-nav-{{type}}">' +
                            '<span class="tui-ico-{{type}}">{{type}}</span>' +
                        '</span>',
            moreButton:
                        '<a href="#" class="lp-button-base lp-button-nav lp-size-medium lp-theme-default lp-variant-flat">' +
                            '<span class="tui-ico-ellip">...</span>' +
                        '</a>'
          }
        }

        const pagination = new tui.Pagination('pagination', options)

        if (max_num_pages < 2) {
          $('#pagination').hide()
        }

        pagination.on('afterMove', (event) => {
          query_vars.paged = event.page

          $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
              action: 'dao-filter-archive-posts',
              query_vars: query_vars,
              filters: $('form.filters').serialize(),
              _wpnonce: nonce
            },
            dataType: 'json',
            error: function (xhr, ajaxOptions, thrownError) {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            },
            success: function (response) {
              if (response.sucsess) {
                $('#data-container').html(response.data.cards)
                if (response.data.max_num_pages >= 2) {
                  if ($('#pagination').is(':hidden')) $('#pagination').show()
                  pagination.reset(response.data.found_posts)
                } else {
                  if (!$('#pagination').is(':hidden')) $('#pagination').hide()
                }
              } else {
                new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
              }
            }
          })
        })

        function get_favourites () {
          $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
              action: get_fav_action,
              query_vars: query_vars,
              filters: $('form.filters').serialize(),
              _wpnonce: get_fav_nonce
            },
            dataType: 'json',
            error: function (xhr, ajaxOptions, thrownError) {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            },
            success: function (response) {
              if (response.success) {
                $('#data-container').html(response.data.cards)
                if (response.data.max_num_pages >= 2) {
                  if ($('#pagination').is(':hidden')) $('#pagination').show()
                  pagination.reset(response.data.found_posts)
                } else {
                  if (!$('#pagination').is(':hidden')) $('#pagination').hide()
                }
              } else {
                new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
              }
            }
          })
        }
      }
    }

    if (path.includes('/user/')) {
      const viewPortfolioModal = new Modal({ selector: $lp('[data-lp-modal=view-portfolio]') })

      $('.lp-portfolio .lp-portfolio__box').on('click', function () {
        const id = $(this).attr('id')

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: {
            action: 'dao-get-portfolio-info',
            id: id,
            type: 'view',
            _wpnonce: get_portfolio_info_nonce
          },
          dataType: 'json',
          error: function (xhr, ajaxOptions, thrownError) {
            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            if (response.success) {
              viewPortfolioModal.handleOpen()
              const modal = $('[data-lp-modal=view-portfolio]')
              modal.find('.title').html(response.data.title)
              modal.find('.categories').html(response.data.categories)
              modal.find('.portfolio-content').html(response.data.content)
              modal.find('.portfolio-gallery').html(response.data.media_files)
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      })

      new Swiper('.page-profile-slider-testimonails', {
        slidesPerView: 3,
        touchRatio: 0.4,
        spaceBetween: 24,
        navigation: {
          prevEl: '.lp-slider__button.lp-prev',
          nextEl: '.lp-slider__button.lp-next'
        }
      })

      $('#data-container').on('click', 'button.add-to-favourites', function (e) {
        const btn = $(this)
        const id = $(this).parents('.lp-card-wrapper').attr('id')
        const icon = '<i class="lp-icon lp-heart-filled"></i>'
        const loader = '<span class="lp-loader"> <svg class="lp-loader__circle" width="25" height="25" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"> <circle class="lp-loader__path" fill="none" stroke-width="5" stroke-linecap="round" cx="24" cy="24" r="20" /> </svg> </span>'

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: {
            action: add_to_fav_action,
            id: id,
            _wpnonce: add_to_fav_nonce
          },
          dataType: 'json',
          beforeSend: function () {
            btn.prop('disabled', true)
            btn.html(loader)
          },
          error: function (xhr, ajaxOptions, thrownError) {
            btn.prop('disabled', false)
            btn.html(icon)

            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            btn.prop('disabled', false)
            btn.html(icon)

            if (response.success) {
              btn.removeClass('add-to-favourites').addClass('delete-from-favourites')
              new Snack({ message: `${response.data.type} "${response.data.title}" добавлено в Избранное.`, variant: 'success' }).show()
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      })
      $('#data-container').on('click', 'button.delete-from-favourites', function (e) {
        const btn = $(this)
        const id = $(this).parents('.lp-card-wrapper').attr('id')
        const icon = '<i class="lp-icon lp-heart-flat"></i>'
        const loader = '<span class="lp-loader"> <svg class="lp-loader__circle" width="25" height="25" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"> <circle class="lp-loader__path" fill="none" stroke-width="5" stroke-linecap="round" cx="24" cy="24" r="20" /> </svg> </span>'

        $.ajax({
          type: 'post',
          url: ajaxurl,
          data: {
            action: delete_from_fav_action,
            id: id,
            _wpnonce: delete_from_fav_nonce
          },
          dataType: 'json',
          beforeSend: function () {
            btn.prop('disabled', true)
            btn.html(loader)
          },
          error: function (xhr, ajaxOptions, thrownError) {
            btn.prop('disabled', false)
            btn.html(icon)

            new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
          },
          success: function (response) {
            btn.prop('disabled', false)
            btn.html(icon)

            if (response.success) {
              btn.removeClass('delete-from-favourites').addClass('add-to-favourites')
              new Snack({ message: `${response.data.type} "${response.data.title}" удалено из Избранное.`, variant: 'success' }).show()
            } else {
              new Snack({ message: 'Произошла ошибка. Попробуйте ещё раз.', variant: 'danger' }).show()
            }
          }
        })
      })

      function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).attr('href')).select();
        document.execCommand("copy");
        $temp.remove();
      }

      $('.lp-common-info__share a.lp-link').on('click', function (e) {
        e.preventDefault()
        copyToClipboard(this)
        new Snack({message: 'Ссылка на профиль скопирована.', variant: 'success'}).show()
      });
    }

    if (path === '/about-us/') {
      $('.lp-textfield').each(function () {
        new TextField({ selector: $lp(this) })
      })
      new Swiper('.page-about-slider-company-develop', {
        slidesPerView: 4,
        touchRatio: 0.4,
        spaceBetween: 24,
        loop: true,
        loopedSlides: 5,
        navigation: {
          prevEl: '.lp-slider__button.lp-prev',
          nextEl: '.lp-slider__button.lp-next'
        },
        scrollbar: {
          el: '.lp-slider__scrollbar'
        }
      })
    }

    if (path.includes('/support/submit-ticket/')) {
      $('.lp-textfield').each(function () {
        new TextField({ selector: $lp(this) })
      })
    }
  })
})
