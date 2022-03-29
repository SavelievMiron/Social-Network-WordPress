class DatePicker {
  /**
   * @constructor
   * @param {Object} el
   */
  constructor(el) {
    this.el = el.selector.nativeElement;
    this.options = el.options;

    if (!(this.el instanceof Element)) throw new Error(el + ' is not a valid Element');
    this.dispatcher = new EventEmitter();

    this.classes = {
      day: 'lp-date-picker__day',
      disabled: 'lp-disabled',
      isPrev: 'lp-prev',
      isCurrent: 'lp-current',
      isNext: 'lp-next',
      selected: 'lp-selected',
      today: 'lp-today',
    };

    this.targets = {
      date: 'date',
      actions: 'actions',
      days: 'days',
      timePicker: 'time-picker',
    };

    this.init();
  }

  prevMonth() {
    this.datePicker.goToPreviousMonth();
    this.renderDatePicker();

    this.dispatcher.emit('date-picker:go-to-prev-month');
  }

  nextMonth() {
    this.datePicker.goToNextMonth();
    this.renderDatePicker();

    this.dispatcher.emit('date-picker:go-to-next-month');
  }

  init() {
    this.format = 'MMM DD, YYYY';
    this.date = new Day(new Date(Date.now()), 'ru-RU');
    this.datePicker = new CalendarData(this.date.year, this.date.monthNumber, 'ru-RU');

    this.render();

    this.currentDate = element('datepicker', this.el, 'date', this.targets);
    this.days = element('datepicker', this.el, 'days', this.targets);
    this.timePicker = new TextField({ selector: element('datepicker', this.el, 'timePicker', this.targets) });
    this.time = null;

    this.renderDatePicker();
    this.initEvents();
    this.dispatcher.emit('date-picker:init');
  }

  initEvents() {
    this.initActions();
    this.initTimePicker();

    this.dispatcher.emit('date-picker:init-events');
  }

  initActions() {
    const [prevButton, nextButton] = element('datepicker', this.el, 'actions', this.targets).children();

    ['click', 'touchend'].map((event) => $lp(prevButton).on(event, (e) => this.prevMonth(e)));
    ['click', 'touchend'].map((event) => $lp(nextButton).on(event, (e) => this.nextMonth(e)));

    this.dispatcher.emit('date-picker:init-actions');
  }

  initTimePicker() {
    $lp(this.timePicker.el).on('change', (e) => {
      this.time = e.target.value;
    });

    this.dispatcher.emit('date-picker:init-time-picker');
  }

  getMonthDaysGrid() {
    const firstDayOfTheMonth = this.datePicker.month.getDay(1);
    const prevMonth = this.datePicker.getPreviousMonth();
    const totalLastMonthFinalDays = firstDayOfTheMonth.dayNumber - 1;
    const totalDays = this.datePicker.month.numberOfDays + totalLastMonthFinalDays;
    const month = Array.from({ length: totalDays });

    for (let i = totalLastMonthFinalDays; i < totalDays; i++)
      month[i] = this.datePicker.month.getDay(i + 1 - totalLastMonthFinalDays);

    for (let i = 0; i < totalLastMonthFinalDays; i++)
      month[i] = prevMonth.getDay(prevMonth.numberOfDays - (totalLastMonthFinalDays - (i + 1)));

    return month;
  }

  renderCurrentDate() {
    const { month, year } = this.datePicker;
    this.currentDate.html('');

    const date = document.createElement('h5');
    $lp(date).attr('aria-label', `Текущая дата - ${month.name} ${year}`).html(`<b>${month.name} ${year}</b>`);
    this.currentDate.append(date);

    this.dispatcher.emit('date-picker:render-current-date');
  }

  renderDays() {
    this.days.html('');

    this.getMonthDaysGrid().map((dayData) => {
      const day = document.createElement('div');

      $lp(day).addClass(this.classes.day).attr('aria-label', dayData.format(this.format)).html(/*html*/ `
        <button class="lp-button-base lp-button-date-picker lp-theme-secondary lp-variant-flat">
          <span data-lp-datepicker="day">${dayData.date}</span>
        </button>
      `);

      ['click', 'touchend'].map((event) => $lp(day).on(event, (e) => this.onSelectDay(day, dayData, e)));

      if (dayData.monthNumber === this.datePicker.month.number) $lp(day).addClass(this.classes.isCurrent);
      else $lp(day).addClass(this.classes.disabled, this.classes.isPrev);

      if (this.options.minDate > dayData.Date || this.options.maxDate < dayData.Date)
        $lp(day).addClass(this.classes.disabled);
      if (this.today(dayData)) $lp(day).addClass(this.classes.today);

      this.days.append(day);
    });

    this.dispatcher.emit('date-picker:render-days');
  }

  renderDatePicker() {
    this.renderCurrentDate();
    this.renderDays();

    this.dispatcher.emit('date-picker:render');
  }

  onSelectDay(day, dayData, e) {
    this.days.children().map((child) => $lp(child).removeClass(this.classes.selected));
    $lp(day).addClass(this.classes.selected);
    this.onSelect && this.onSelect({ target: day, time: this.time, data: dayData, e });

    this.dispatcher.emit('date-picker:select-day', { target: day, time: this.time, data: dayData, e });
  }

  today(date) {
    return date.date === this.date.date && date.monthNumber === this.date.monthNumber && date.year === this.date.year;
  }

  render() {
    const { weekDays } = this.datePicker;

    $lp(this.el).html(/*html*/ `
      <header class="lp-date-picker__header lp-flex lp-align-center lp-justify-between">
        <div class="lp-date-picker__date" data-lp-datepicker="date"></div>

        <div class="lp-date-picker__actions" data-lp-datepicker="actions">
          <button
            class="lp-button-base lp-button-icon lp-size-small lp-theme-secondary lp-variant-flat"
            aria-label="Переключить на предыдущий месяц"
          >
            <i class="lp-icon lp-angle-left-flat"></i>
          </button>

          <button
            class="lp-button-base lp-button-icon lp-size-small lp-theme-secondary lp-variant-flat"
            aria-label="Переключить на следующий месяц"
          >
            <i class="lp-icon lp-angle-right-flat"></i>
          </button>
        </div>
      </header>

      <div class="lp-date-picker__body">
        <div class="lp-date-picker__week">
          ${weekDays.map((weekDay) => /*html*/ `<div class="lp-date-picker__week-day">${weekDay}</div>`).join(' ')}
        </div>

        <div class="lp-date-picker__days" data-lp-datepicker="days"></div>

        <div
          class="lp-textfield lp-variant-outlined lp-date-picker__textfield"
          data-lp-datepicker="time-picker">
          <div class="lp-textfield__prefix">
            <span class="lp-typo lp-footnote lp-grey">Время</span>
          </div>
  
          <div class="lp-textfield__input">
            <label class="lp-textfield__label">
              <input type="time" data-lp-textfield="input" />
            </label>
          </div>

          <div class="lp-textfield__postfix">
            <span class="lp-typo lp-footnote lp-grey">МСК</span>
          </div>
        </div>
      </div>
    `);
  }
}
