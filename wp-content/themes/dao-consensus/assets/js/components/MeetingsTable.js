class MeetingsTableRow extends TableRowMenu {
  /**
   * @constructor
   * @param {Object} el
   */
  constructor(el) {
    super(el);
  }

  init() {
    super.init();

    var type = $lp(this.el).attr('data-row-type'),
    status = $lp(this.el).attr('data-status');


    var content = '';

    /* sent and received invitation */
    if (type === 'sent') {

      if (status === 'accepted') {
        content = `
          <ul class="lp-list lp-no-style lp-flex lp-direction-column" data-lp-row="options">
            <li class="view lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
              <i class="lp-icon lp-prefix lp-eye-flat"></i>
              <span>Просмотреть</span>
            </li>
            <li class="reschedule lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
              <i class="lp-icon lp-prefix lp-history-flat"></i>
              <span>Перенести</span>
            </li>
            <li class="cancel lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
              <i class="lp-icon lp-prefix lp-times-flat"></i>
              <span>Отменить</span>
            </li>
          </ul>
        `;
      } else if (status === 'waiting') {
        content = `
          <ul class="lp-list lp-no-style lp-flex lp-direction-column" data-lp-row="options">
            <li class="view lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
              <i class="lp-icon lp-prefix lp-eye-flat"></i>
              <span>Просмотреть</span>
            </li>
          </ul>
        `;
      } else if (status === 'canceled' || status === 'went') {
        content = `
          <ul class="lp-list lp-no-style lp-flex lp-direction-column" data-lp-row="options">
            <li class="view lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
              <i class="lp-icon lp-prefix lp-eye-flat"></i>
              <span>Просмотреть</span>
            </li>
          </ul>
        `;
      } else if (status === 'reschedule') {
        const initiator = $lp(this.el).attr('data-lp-reschedule-initiator');
        if (initiator === 'true') {
          content = `
          <ul class="lp-list lp-no-style lp-flex lp-direction-column" data-lp-row="options">
            <li class="view lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
              <i class="lp-icon lp-prefix lp-eye-flat"></i>
              <span>Просмотреть</span>
            </li>
          </ul>
        `;
        } else {
          content = `
          <ul class="lp-list lp-no-style lp-flex lp-direction-column" data-lp-row="options">
            <li class="view lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
              <i class="lp-icon lp-prefix lp-eye-flat"></i>
              <span>Просмотреть</span>
            </li>
            <li class="confirm-reschedule lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
              <i class="lp-icon lp-prefix lp-check-flat"></i>
              <span>Подтвердить</span>
            </li>
            <li class="reject-reschedule lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
              <i class="lp-icon lp-prefix lp-times-flat"></i>
              <span>Отклонить</span>
            </li>
          </ul>
        `;
        }
      } else {
        content = `
        <ul class="lp-list lp-no-style lp-flex lp-direction-column" data-lp-row="options">
          <li class="view lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
            <i class="lp-icon lp-prefix lp-eye-flat"></i>
            <span>Просмотреть</span>
          </li>
        </ul>
      `;
      }

    } else if (type === 'received') {

      if (status === 'waiting') {
        content = `
          <ul class="lp-list lp-no-style lp-flex lp-direction-column" data-lp-row="options">
            <li class="view lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
              <i class="lp-icon lp-prefix lp-eye-flat"></i>
              <span>Просмотреть</span>
            </li>
            <li class="confirm lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
              <i class="lp-icon lp-prefix lp-check-flat"></i>
              <span>Подтвердить</span>
            </li>
            <li class="reject lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
              <i class="lp-icon lp-prefix lp-times-flat"></i>
              <span>Отклонить</span>
            </li>
          </ul>
        `;
      } else if (status === 'accepted') {
        content = `
          <ul class="lp-list lp-no-style lp-flex lp-direction-column" data-lp-row="options">
            <li class="view lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
              <i class="lp-icon lp-prefix lp-eye-flat"></i>
              <span>Просмотреть</span>
            </li>
            <li class="reschedule lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
              <i class="lp-icon lp-prefix lp-history-flat"></i>
              <span>Перенести</span>
            </li>
          </ul>
        `;
      } else if (status === 'reschedule') {
        let initiator = $lp(this.el).attr('data-lp-reschedule-initiator')
        if (initiator === 'true') {
          content = `
          <ul class="lp-list lp-no-style lp-flex lp-direction-column" data-lp-row="options">
            <li class="view lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
              <i class="lp-icon lp-prefix lp-eye-flat"></i>
              <span>Просмотреть</span>
            </li>
          </ul>
        `;
        } else {
          content = `
          <ul class="lp-list lp-no-style lp-flex lp-direction-column" data-lp-row="options">
            <li class="view lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
              <i class="lp-icon lp-prefix lp-eye-flat"></i>
              <span>Просмотреть</span>
            </li>
            <li class="confirm-reschedule lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
              <i class="lp-icon lp-prefix lp-check-flat"></i>
              <span>Подтвердить</span>
            </li>
            <li class="reject-reschedule lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
              <i class="lp-icon lp-prefix lp-times-flat"></i>
              <span>Отклонить</span>
            </li>
          </ul>
        `;
        }
      } else if (status === 'declined' || status === 'canceled' || status === 'went') {
        content = `
          <ul class="lp-list lp-no-style lp-flex lp-direction-column" data-lp-row="options">
            <li class="view lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
              <i class="lp-icon lp-prefix lp-eye-flat"></i>
              <span>Просмотреть</span>
            </li>
          </ul>
        `;
      } else {
        content = `
          <ul class="lp-list lp-no-style lp-flex lp-direction-column" data-lp-row="options">
            <li class="view lp-button-base lp-button-option lp-theme-secondary lp-variant-flat">
              <i class="lp-icon lp-prefix lp-eye-flat"></i>
              <span>Просмотреть</span>
            </li>
          </ul>
        `;
      }

    }

    $lp(this.popoverEl)
      .attr('class', 'lp-paper lp-outlined lp-elevation lp-dense-3 lp-popover')
      .attr('data-lp-row', 'popover')
      .attr('aria-hidden', true).html(content);
  }

  setOptions() {
    super.setOptions();

    const [viewButton, confirmButton, rescheduleButton, rejectButton, cancelButton] = this.list.children();
    this.viewButton = viewButton;
    this.confirmButton = confirmButton;
    this.rescheduleButton = rescheduleButton;
    this.rejectButton = rejectButton;
    this.cancelButton = cancelButton;
  }

  /**
   *
   * @param {KeyboardEvent} e
   * @param {DomManipulator} option
   */
  handleKeyDownOption(e) {
    super.handleKeyDownOption(e);

    switch (e.key) {
      case this.keys.enter:
        switch (this.activeOptionIndex) {
          case 0:
            this.handleViewRow();
            break;

          case 1:
            this.handleConfirmRow();
            break;

          case 2:
            this.handleRejectRow();
            break;

          case 3:
            this.handleRescheduleRow();
            break;

          case 4:
            this.handleCancelRow();
            break;
        }

        break;
    }
  }

  handleViewRow() {
    this.activeOptionIndex = 0;
    this.handleSelectOption();

    this.onView && this.onView(this.el);
  }

  handleConfirmRow() {
    this.activeOptionIndex = 1;
    this.handleSelectOption();

    this.onConfirm && this.onConfirm(this.el);
  }

  handleRejectRow() {
    this.activeOptionIndex = 2;
    this.handleSelectOption();

    this.onReject && this.onReject(this.el);
  }

  handleRescheduleRow() {
    this.activeOptionIndex = 3;
    this.handleSelectOption();

    this.onReschedule && this.onReschedule(this.el);
  }

  handleCancelRow() {
    this.activeOptionIndex = 4;
    this.handleSelectOption();

    this.onCancel && this.onCancel(this.el);
  }

  initOptionsEvents() {
    super.initOptionsEvents();

    var type = $lp(this.el).attr('data-row-type'),
    status = $lp(this.el).attr('data-status');

    if (type === 'sent') {
      if (status === 'waiting') {
        this.handleViewRow = this.handleViewRow.bind(this);
        ['click', 'touchend'].map((event) => $lp(this.viewButton).on(event, this.handleViewRow));
      } else if (status === 'accepted') {
        this.handleViewRow = this.handleViewRow.bind(this);
        this.handleRescheduleRow = this.handleRescheduleRow.bind(this);
        this.handleCancelRow = this.handleCancelRow.bind(this);
        ['click', 'touchend'].map((event) => $lp(this.viewButton).on(event, this.handleViewRow));
        ['click', 'touchend'].map((event) => $lp(this.rescheduleButton).on(event, this.handleRescheduleRow));
        ['click', 'touchend'].map((event) => $lp(this.cancelButton).on(event, this.handleCancelRow));
      } else if (status === 'reschedule') {
        let initiator = $lp(this.el).attr('data-lp-reschedule-initiator');
        this.handleViewRow = this.handleViewRow.bind(this);
        ['click', 'touchend'].map((event) => $lp(this.viewButton).on(event, this.handleViewRow));
        if (initiator === 'false') {
          this.handleConfirmRow = this.handleConfirmRow.bind(this);
          this.handleRejectRow = this.handleRejectRow.bind(this);
          ['click', 'touchend'].map((event) => $lp(this.confirmButton).on(event, this.handleConfirmRow));
          ['click', 'touchend'].map((event) => $lp(this.rejectButton).on(event, this.handleRejectRow));
  
        }
      } else if (['declined', 'canceled', 'went'].includes(status)) {
        this.handleViewRow = this.handleViewRow.bind(this);
        ['click', 'touchend'].map((event) => $lp(this.viewButton).on(event, this.handleViewRow));
      } else {
        this.handleViewRow = this.handleViewRow.bind(this);
        ['click', 'touchend'].map((event) => $lp(this.viewButton).on(event, this.handleViewRow));
      }
    } else if (type === 'received') {
      if (status === 'waiting') {
        this.handleViewRow = this.handleViewRow.bind(this);
        this.handleConfirmRow = this.handleConfirmRow.bind(this);
        this.handleRejectRow = this.handleRejectRow.bind(this);
        ['click', 'touchend'].map((event) => $lp(this.viewButton).on(event, this.handleViewRow));
        ['click', 'touchend'].map((event) => $lp(this.confirmButton).on(event, this.handleConfirmRow));
        ['click', 'touchend'].map((event) => $lp(this.rejectButton).on(event, this.handleRejectRow));
      } else if (status === 'accepted') {
        this.handleViewRow = this.handleViewRow.bind(this);
        this.handleRescheduleRow = this.handleRescheduleRow.bind(this);
        ['click', 'touchend'].map((event) => $lp(this.viewButton).on(event, this.handleViewRow));
        ['click', 'touchend'].map((event) => $lp(this.rescheduleButton).on(event, this.handleRescheduleRow));
      } else if (status === 'reschedule') {
        let initiator = $lp(this.el).attr('data-lp-reschedule-initiator');
        this.handleViewRow = this.handleViewRow.bind(this);
        ['click', 'touchend'].map((event) => $lp(this.viewButton).on(event, this.handleViewRow));
        if (initiator === 'false') {
          this.handleConfirmRow = this.handleConfirmRow.bind(this);
          this.handleRejectRow = this.handleRejectRow.bind(this);
          ['click', 'touchend'].map((event) => $lp(this.confirmButton).on(event, this.handleConfirmRow));
          ['click', 'touchend'].map((event) => $lp(this.rejectButton).on(event, this.handleRejectRow));
  
        }      
      } else if (['declined', 'canceled', 'went'].includes(status)) {
        this.handleViewRow = this.handleViewRow.bind(this);
        ['click', 'touchend'].map((event) => $lp(this.viewButton).on(event, this.handleViewRow));
      } else {
        this.handleViewRow = this.handleViewRow.bind(this);
        ['click', 'touchend'].map((event) => $lp(this.viewButton).on(event, this.handleViewRow));
      }
    }

  }

  destroyOptionsEvents() {
    super.destroyOptionsEvents();

    var type = $lp(this.el).attr('data-row-type'),
    status = $lp(this.el).attr('data-status');

    if (type === 'sent') {
      if (status === 'waiting') {
        this.handleViewRow = this.handleViewRow.bind(this);
        ['click', 'touchend'].map((event) => $lp(this.viewButton).off(event, this.handleViewRow));
      } else if (status === 'accepted') {
        this.handleViewRow = this.handleViewRow.bind(this);
        this.handleRescheduleRow = this.handleRescheduleRow.bind(this);
        this.handleCancelRow = this.handleCancelRow.bind(this);
        ['click', 'touchend'].map((event) => $lp(this.viewButton).off(event, this.handleViewRow));
        ['click', 'touchend'].map((event) => $lp(this.rescheduleButton).off(event, this.handleRescheduleRow));
        ['click', 'touchend'].map((event) => $lp(this.cancelButton).off(event, this.handleCancelRow));
      } else if (status === 'reschedule') {
        let initiator = $lp(this.el).attr('data-lp-reschedule-initiator');
        this.handleViewRow = this.handleViewRow.bind(this);
        ['click', 'touchend'].map((event) => $lp(this.viewButton).off(event, this.handleViewRow));
        if (initiator === 'false') {
          this.handleConfirmRow = this.handleConfirmRow.bind(this);
          this.handleRejectRow = this.handleRejectRow.bind(this);
          ['click', 'touchend'].map((event) => $lp(this.confirmButton).off(event, this.handleConfirmRow));
          ['click', 'touchend'].map((event) => $lp(this.rejectButton).off(event, this.handleRejectRow));
        }
      } else if (['declined', 'canceled', 'went'].includes(status)) {
        this.handleViewRow = this.handleViewRow.bind(this);
        ['click', 'touchend'].map((event) => $lp(this.viewButton).off(event, this.handleViewRow));
      } else {
        this.handleViewRow = this.handleViewRow.bind(this);
        ['click', 'touchend'].map((event) => $lp(this.viewButton).off(event, this.handleViewRow));
      }
    } else if (type === 'received') {
      if (status === 'waiting') {
        this.handleViewRow = this.handleViewRow.bind(this);
        this.handleConfirmRow = this.handleConfirmRow.bind(this);
        this.handleRejectRow = this.handleRejectRow.bind(this);
        ['click', 'touchend'].map((event) => $lp(this.viewButton).off(event, this.handleViewRow));
        ['click', 'touchend'].map((event) => $lp(this.confirmButton).off(event, this.handleConfirmRow));
        ['click', 'touchend'].map((event) => $lp(this.rejectButton).off(event, this.handleRejectRow));
      } else if (status === 'accepted') {
        this.handleViewRow = this.handleViewRow.bind(this);
        this.handleRescheduleRow = this.handleRescheduleRow.bind(this);
        ['click', 'touchend'].map((event) => $lp(this.viewButton).off(event, this.handleViewRow));
        ['click', 'touchend'].map((event) => $lp(this.rescheduleButton).off(event, this.handleRescheduleRow));
      } else if (status === 'reschedule') {
        let initiator = $lp(this.el).attr('data-lp-reschedule-initiator');
        this.handleViewRow = this.handleViewRow.bind(this);
        ['click', 'touchend'].map((event) => $lp(this.viewButton).off(event, this.handleViewRow));
        if (initiator === 'false') {
          this.handleConfirmRow = this.handleConfirmRow.bind(this);
          this.handleRejectRow = this.handleRejectRow.bind(this);
          ['click', 'touchend'].map((event) => $lp(this.confirmButton).off(event, this.handleConfirmRow));
          ['click', 'touchend'].map((event) => $lp(this.rejectButton).off(event, this.handleRejectRow));
        }
      } else if (['declined', 'canceled', 'went'].includes(status)) {
        this.handleViewRow = this.handleViewRow.bind(this);
        ['click', 'touchend'].map((event) => $lp(this.viewButton).off(event, this.handleViewRow));
      } else {
        this.handleViewRow = this.handleViewRow.bind(this);
        ['click', 'touchend'].map((event) => $lp(this.viewButton).off(event, this.handleViewRow));
      }
    }
  }
}

class MeetingsTable {
  /**
   * @constructor
   * @param {Object} el
   */
  constructor(el) {
    this.el = el.selector.nativeElement;

    if (!(this.el instanceof Element)) throw new Error(el + ' is not a valid Element');
    this.dispatcher = new EventEmitter();

    this.initTable();
  }

  initTable() {
    this.rows = $lp(this.el).find('tbody').children();
    this.rows.forEach((row, id) => {
      const tableRow = new MeetingsTableRow({ selector: $lp(row) });
      $lp(tableRow.el).attr('data-lp-id', id);

      var type = $lp(tableRow.el).attr('data-row-type'),
      status = $lp(tableRow.el).attr('data-status');

      if (type === 'sent') {
        if (status === 'waiting') {
          tableRow.onView = (row) => this.onViewRow && this.onViewRow({ row, id });
        } else if (status === 'accepted') {
          tableRow.onView = (row) => this.onViewRow && this.onViewRow({ row, id });
          tableRow.onReschedule = (row) => this.onRescheduleRow && this.onRescheduleRow({ row, id });
          tableRow.onCancel = (row) => this.onCancelRow && this.onCancelRow({ row, id });
        } else if (status === 'reschedule') {
          tableRow.onView = (row) => this.onViewRow && this.onViewRow({ row, id });
          tableRow.onConfirm = (row) => this.onConfirmRow && this.onConfirmRow({ row, id });
          tableRow.onReject = (row) => this.onRejectRow && this.onRejectRow({ row, id });    
        } else if (['declined', 'canceled', 'went'].includes(status)) {
          tableRow.onView = (row) => this.onViewRow && this.onViewRow({ row, id });
        } else {
          tableRow.onView = (row) => this.onViewRow && this.onViewRow({ row, id });
        }
      } else if (type === 'received') {
        if (status === 'watings') {
          tableRow.onView = (row) => this.onViewRow && this.onViewRow({ row, id });
          tableRow.onConfirm = (row) => this.onConfirmRow && this.onConfirmRow({ row, id });
          tableRow.onReject = (row) => this.onRejectRow && this.onRejectRow({ row, id });    
        } else if (status === 'accepted') {
          tableRow.onView = (row) => this.onViewRow && this.onViewRow({ row, id });
          tableRow.onReschedule = (row) => this.onRescheduleRow && this.onRescheduleRow({ row, id });
        } else if (status === 'reschedule') {
          tableRow.onView = (row) => this.onViewRow && this.onViewRow({ row, id });
          tableRow.onConfirm = (row) => this.onConfirmRow && this.onConfirmRow({ row, id });
          tableRow.onReject = (row) => this.onRejectRow && this.onRejectRow({ row, id });    
        } else if (['declined', 'canceled', 'went'].includes(status)) {
          tableRow.onView = (row) => this.onViewRow && this.onViewRow({ row, id });
        } else {
          tableRow.onView = (row) => this.onViewRow && this.onViewRow({ row, id });
        }
      }
    });

    this.dispatcher.emit('table:init');
  }
}
