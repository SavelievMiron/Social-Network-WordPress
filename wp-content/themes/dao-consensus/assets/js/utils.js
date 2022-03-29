const _lp = {
  /**
   *
   * @param {Number} ms
   * @returns {Promise<any>} Promise
   */
  delay(ms = 1000) {
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve();
      }, ms);
    });
  },

  /**
   *
   * @param {any} v
   * @returns {Boolean}
   */
  isString(v) {
    return v === String(v);
  },

  /**
   *
   * @param {any} v
   * @returns {Boolean}
   */
  isUndefined(v) {
    return v === undefined || v === null;
  },

  /**
   *
   * @param {any} v
   * @returns {Boolean}
   */
  isDefined(v) {
    return v !== undefined && v !== null;
  },

  /**
   *
   * @param {any} v
   * @returns {Boolean}
   */
  isTrue(v) {
    return v === true;
  },

  /**
   *
   * @param {any} v
   * @returns {Boolean}
   */
  isFalse(v) {
    return v === false;
  },
};

function $lp(el) {
  return new DomManipulator(el);
}

/**
 *
 * @param {Date} date
 * @returns {Number}
 */
function getWeekNumber(date) {
  const firstDayOfTheYear = new Date(date.getFullYear(), 0, 1);
  const pastDaysOfYear = (date - firstDayOfTheYear) / 86400000;

  return Math.ceil((pastDaysOfYear + firstDayOfTheYear.getDay() + 1) / 7);
}

/**
 *
 * @param {Number} year
 * @returns {Boolean}
 */
function isLeapYear(year) {
  return year % 100 === 0 ? year % 400 === 0 : year % 4 === 0;
}

/**
 *
 * @param {DomManipulator} parent
 * @param {DomManipulator} element
 * @returns {DomManipulator} element
 */
const element = (name, parent, element, targets) => $lp(parent).find(`[data-lp-${name}="${targets[element]}"]`);

/**
 * TRANSITIONS
 */
const TRANSITIONS = {
  scaleUp: (position) => `scale-up-${position}`,
  fade: 'fade',
};

const transition = (transition) => ({
  enter: `lp-${transition}-enter`,
  enterActive: `lp-${transition}-enter-active`,
  exit: `lp-${transition}-exit`,
  exitActive: `lp-${transition}-exit-active`,
});

class DomManipulator {
  /**
   *
   * @param {Element} el
   */
  constructor(el) {
    if (_lp.isString(el)) el = document.querySelector(el);

    this.nativeElement = el;
    this.isManipulable = true;
  }

  /**
   *
   * @param {Event} event
   * @param {Function} callback
   * @param  {...any} options
   * @returns {DomManipulator} this
   */
  on(event, callback, ...options) {
    this.nativeElement.addEventListener(event, callback, ...options);

    return this;
  }

  /**
   *
   * @param {Event} event
   * @param {Function} callback
   * @param  {...any} options
   * @returns {DomManipulator} this
   */
  off(event, callback, ...options) {
    this.nativeElement.removeEventListener(event, callback, ...options);

    return this;
  }

  /**
   *
   * @param {DomManipulator} selector
   * @returns {DomManipulator} this
   */
  find(selector) {
    return $lp(this.nativeElement.querySelector(selector));
  }

  /**
   *
   * @param {DomManipulator} selector
   * @returns {DomManipulator[]} this
   */
  findAll(selector) {
    return Array.from(this.nativeElement.querySelectorAll(selector)).map((el) => $lp(el));
  }

  /**
   *
   * @returns {HTMLCollection}
   */
  children() {
    return Array.from(this.nativeElement.children);
  }

  /**
   *
   * @returns {Element} last child element
   */
  last() {
    return this.nativeElement.lastElementChild;
  }

  /**
   *
   * @returns {String} text value
   */
  text(value) {
    if (_lp.isUndefined(value)) return this.nativeElement.innerText;
    this.nativeElement.innerText = value;

    return this;
  }

  /**
   *
   * @param {String} value
   * @returns {DomManipulator} this
   */
  value(value) {
    if (_lp.isUndefined(value)) return this.nativeElement.value;
    this.nativeElement.value = value;

    return this;
  }

  /**
   *
   * @param {DomManipulator} html
   * @returns {DomManipulator} this
   */
  html(html) {
    if (html.isManipulable) html = html.nativeElement.innerHTML;
    this.nativeElement.innerHTML = html;

    return this;
  }

  /**
   *
   * @param {CSSStyleDeclaration} styles
   * @returns {DomManipulator} this
   */
  css(styles) {
    if (_lp.isUndefined(styles)) return this.nativeElement.style;

    Object.keys(styles).forEach((style) => {
      this.nativeElement.style[style] = styles[style];
    });

    return this;
  }

  /**
   *
   * @param {String} name
   * @param {String} value
   * @returns {DomManipulator} this
   */
  attr(name, value) {
    if (_lp.isUndefined(value)) return this.nativeElement.getAttribute(name);
    this.nativeElement.setAttribute(name, value);

    return this;
  }

  /**
   *
   * @returns {DomManipulator} parent element
   */
  parent() {
    return $lp(this.nativeElement.parentNode);
  }

  /**
   *
   * @param {DomMainpulator} el
   * @returns {DomMainpulator} this
   */
  append(el) {
    if (el.isManipulable) el = el.nativeElement;
    this.nativeElement.appendChild(el);

    return this;
  }

  /**
   *
   * @returns {DomMainpulator} this
   */
  remove() {
    this.nativeElement.remove();

    return this;
  }

  /**
   *
   * @param {DomMainpulator} el
   * @returns {DomMainpulator} this
   */
  wrap(el) {
    if (el.isManipulable) el = el.nativeElement;

    this.nativeElement.parentNode.insertBefore(el, this.nativeElement);
    el.appendChild(this.nativeElement);

    return this;
  }

  /**
   *
   * @param {DomMainpulator} el
   * @returns {DomMainpulator} this
   */
  unwrap(el) {
    if (el.isManipulable) el = el.nativeElement;
    el.replaceWith(...el.childNodes);

    return this;
  }

  /**
   *
   * @param {String} className
   * @returns {DomMainpulator} this
   */
  addClass(className) {
    this.nativeElement.classList.add(className);

    return this;
  }

  /**
   *
   * @param {String} className
   * @returns {DomMainpulator} this
   */
  removeClass(className) {
    this.nativeElement.classList.remove(className);

    return this;
  }

  /**
   *
   * @param {String} className
   * @returns {DomMainpulator} this
   */
  toggleClass(className) {
    this.nativeElement.classList.toggle(className);

    return this;
  }

  /**
   *
   * @param {String} className
   * @returns {Boolean}
   */
  hasClass(className) {
    return this.nativeElement.classList.contains(className);
  }

  /**
   *
   * @returns {DomManipulator} this
   */
  focus() {
    this.nativeElement.focus();

    return this;
  }

  /**
   *
   * @returns {DomManipulator} this
   */
  blur() {
    this.nativeElement.blur();

    return this;
  }
}

class Day {
  /**
   *
   * @constructor
   * @param {Date} date
   * @param {String} lang
   */
  constructor(date = null, lang = 'default') {
    date = date ?? new Date();

    this.Date = date;
    this.date = date.getDate();
    this.day = date.toLocaleString(lang, { weekday: 'long' });
    this.dayNumber = date.getDay() + 1;
    this.dayShort = date.toLocaleString(lang, { weekday: 'short' });
    this.year = date.getFullYear();
    this.yearShort = date.toLocaleString(lang, { year: '2-digit' });
    this.month = date.toLocaleString(lang, { month: 'long' });
    this.monthShort = date.toLocaleString(lang, { month: 'short' });
    this.monthNumber = date.getMonth() + 1;
    this.timestamp = date.getTime();
    this.week = getWeekNumber(date);
  }

  get isToday() {
    return this.isEqualTo(new Date());
  }

  /**
   *
   * @param {Date} date
   * @returns {Boolean}
   */
  isEqualTo(date) {
    date = date instanceof Day ? date.Date : date;

    return date.getDate() === this.date && date.getMonth() === this.monthNumber - 1 && date.getFullYear() === this.year;
  }

  /**
   *
   * @param {String} formatStr
   * @returns {String}
   */
  format(formatStr) {
    return formatStr
      .replace(/\bYYYY\b/, this.year)
      .replace(/\bYYY\b/, this.yearShort)
      .replace(/\bWW\b/, this.week.toString().padStart(2, '0'))
      .replace(/\bW\b/, this.week)
      .replace(/\bDDDD\b/, this.day)
      .replace(/\bDDD\b/, this.dayShort)
      .replace(/\bDD\b/, this.date.toString().padStart(2, '0'))
      .replace(/\bD\b/, this.date)
      .replace(/\bMMMM\b/, this.month)
      .replace(/\bMMM\b/, this.monthShort)
      .replace(/\bMM\b/, this.monthNumber.toString().padStart(2, '0'))
      .replace(/\bM\b/, this.monthNumber);
  }
}

class Month {
  /**
   *
   * @param {Date} date
   * @param {String} lang
   */
  constructor(date = null, lang = 'default') {
    const day = new Day(date, lang);
    const monthsSize = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    this.lang = lang;

    this.name = day.month;
    this.number = day.monthNumber;
    this.year = day.year;
    this.numberOfDays = monthsSize[this.number - 1];

    if (this.number === 2) this.numberOfDays += isLeapYear(day.year) ? 1 : 0;

    this[Symbol.iterator] = function* () {
      let number = 1;
      yield this.getDay(number);
      while (number < this.numberOfDays) {
        ++number;
        yield this.getDay(number);
      }
    };
  }

  /**
   *
   * @param {Date} date
   * @returns {Day}
   */
  getDay(date) {
    return new Day(new Date(this.year, this.number - 1, date), this.lang);
  }
}

class CalendarData {
  weekDays = Array.from({ length: 7 });

  /**
   *
   * @constructor
   * @param {Date} year
   * @param {Number} monthNumber
   * @param {String} lang
   */
  constructor(year = null, monthNumber = null, lang = 'default') {
    this.today = new Day(null, lang);
    this.year = year ?? this.today.year;
    this.month = new Month(new Date(this.year, (monthNumber || this.today.monthNumber) - 1), lang);
    this.lang = lang;

    this[Symbol.iterator] = function* () {
      let number = 1;
      yield this.getMonth(number);
      while (number < 12) {
        ++number;
        yield this.getMonth(number);
      }
    };

    this.weekDays.forEach((_, i) => {
      const { dayShort, dayNumber } = this.month.getDay(i + 1);
      if (!this.weekDays.includes(dayShort)) this.weekDays[dayNumber - 1] = dayShort;
    });
  }

  get isLeapYear() {
    return isLeapYear(this.year);
  }

  /**
   *
   * @param {Number} monthNumber
   * @returns {Month}
   */
  getMonth(monthNumber) {
    return new Month(new Date(this.year, monthNumber - 1), this.lang);
  }

  /**
   *
   * @returns {Month}
   */
  getPreviousMonth() {
    if (this.month.number === 1) return new Month(new Date(this.year - 1, 11), this.lang);

    return new Month(new Date(this.year, this.month.number - 2), this.lang);
  }

  /**
   *
   * @returns {Month}
   */
  getNextMonth() {
    if (this.month.number === 12) return new Month(new Date(this.year + 1, 0), this.lang);

    return new Month(new Date(this.year, this.month.number + 2), this.lang);
  }

  /**
   *
   * @param {Number} monthNumber
   * @param {Number | Date} year
   */
  goToDate(monthNumber, year) {
    this.month = new Month(new Date(year, monthNumber - 1), this.lang);
    this.year = year;
  }

  goToNextYear() {
    this.year += 1;
    this.month = new Month(new Date(this.year, 0), this.lang);
  }

  goToPreviousYear() {
    this.year -= 1;
    this.month = new Month(new Date(this.year, 11), this.lang);
  }

  goToNextMonth() {
    if (this.month.number === 12) return this.goToNextYear();

    this.month = new Month(new Date(this.year, this.month.number + 1 - 1), this.lang);
  }

  goToPreviousMonth() {
    if (this.month.number === 1) return this.goToPreviousYear();

    this.month = new Month(new Date(this.year, this.month.number - 1 - 1), this.lang);
  }
}

class EventEmitter {
  /**
   * @constructor
   */
  constructor() {
    this.events = {};
  }

  /**
   *
   * @param {String} event
   * @param {Function} listener
   * @returns {EventEmitter}
   */
  on(event, listener) {
    (this.events[event] || (this.events[event] = [])).push(listener);
    return this;
  }

  /**
   *
   * @param {String} event
   * @returns {EventEmitter}
   */
  off(event) {
    delete this.events[event];
    return this;
  }

  /**
   *
   * @param {String} event
   * @param  {...any} args
   * @returns {EventEmitter}
   */
  emit(event, ...args) {
    (this.events[event] || []).slice().forEach((update) => update(...args));
    return this;
  }
}

class TableRowMenu {
  /**
   * @constructor
   * @param {Object} el
   */
  constructor(el) {
    this.el = el.selector.nativeElement;

    if (!(this.el instanceof Element)) throw new Error(el + ' is not a valid Element');
    this.dispatcher = new EventEmitter();

    this.keys = {
      arrowDown: 'ArrowDown',
      arrowUp: 'ArrowUp',
      enter: 'Enter',
      escape: 'Escape',
    };

    this.targets = {
      button: 'button',
      options: 'options',
    };

    this.classes = {
      selected: 'lp-selected',
    };

    this.init();
  }

  init() {
    this.button = element('row', this.el, 'button', this.targets);
    this.popoverWrapper = this.button.parent();
    this.popoverEl = document.createElement('div');

    this.popover = new Transition({
      selector: $lp(this.popoverEl),
      transition: TRANSITIONS.scaleUp('top-right'),
    });

    this.initEvents();
    this.initDispatcher();
    this.dispatcher.emit('row:init');
  }

  initEvents() {
    this.initButtonsEvents();
    this.initDocumentEvents();

    this.dispatcher.emit('row:init-events');
  }

  initDispatcher() {
    this.dispatcher.on('options:open', () => {
      this.setOptions();
      this.initOptionsEvents();
      this.setFocusedOption();
    });

    ['option:select', 'option:focus'].map((event) => {
      this.dispatcher.on(event, () => {
        this.setFocusedOption();
      });
    });

    this.dispatcher.on('options:close', () => {
      this.destroyOptionsEvents();
    });

    this.dispatcher.on('option:select', () => {
      this.popover.dispatcher.on('transition:exit', () => {
        this.button.focus();
      });
    });
  }

  setOptions() {
    this.list = element('row', this.el, 'options', this.targets);
    this.options = this.list.findAll('li');
    this.list.attr('role', 'listbox').attr('tabindex', -1);
    this.activeOptionIndex = this.activeOptionIndex || 0;

    this.options.forEach((option, id) => {
      option
        .attr('data-lp-option', `option-${id}`)
        .attr('data-lp-value', option.find('span').text())
        .attr('aria-disabled', false)
        .attr('aria-selected', false)
        .attr('tabindex', -1)
        .attr('role', 'option');
    });

    this.dispatcher.emit('options:set');
  }

  setFocusedOption() {
    this.options[this.activeOptionIndex].focus();
  }

  /**
   *
   * @param {MouseEvent | TouchEvent} e
   */
  handleOpenOptions(e) {
    this.optionsListOpened = true;
    this.popoverWrapper.append(this.popoverEl);
    this.popover.enter();
    $lp(this.popoverEl).attr('aria-hidden', false);

    this.dispatcher.emit('options:open', e);
  }

  /**
   *
   * @param {MouseEvent | TouchEvent} e
   */
  handleCloseOptions(e) {
    this.optionsListOpened = false;
    this.popover.exit();
    this.popover.dispatcher.on('transition:exit', () => $lp(this.popoverEl).attr('aria-hidden', false).remove());

    this.dispatcher.emit('options:close', e);
  }

  handleSelectOption() {
    this.options.forEach((option) =>
      option.removeClass(this.classes.selected).attr('aria-selected', false).attr('tabindex', -1),
    );

    this.options[this.activeOptionIndex]
      .addClass(this.classes.selected)
      .attr('aria-selected', true)
      .attr('tabindex', 0);
    this.handleCloseOptions();
    this.dispatcher.emit('option:select');
  }

  /**
   *
   * @param {KeyboardEvent} e
   * @param {DomManipulator} option
   */
  handleKeyDownOption(e) {
    e.preventDefault();

    switch (e.key) {
      case this.keys.arrowDown:
        ++this.activeOptionIndex;
        if (this.activeOptionIndex >= this.options.length) this.activeOptionIndex = 0;

        this.dispatcher.emit('option:focus');
        break;

      case this.keys.arrowUp:
        if (!this.activeOptionIndex) this.activeOptionIndex = this.options.length;
        this.activeOptionIndex--;

        this.dispatcher.emit('option:focus');
        break;
    }
  }

  /**
   *
   * @param {MouseEvent | TouchEvent} e
   */
  handleClickOutsideOfMenu(e) {
    const { target } = e;

    if (this.optionsListOpened && !this.el.contains(target)) this.handleCloseOptions();
  }

  initButtonsEvents() {
    this.handleOpenOptions = this.handleOpenOptions.bind(this);

    ['click', 'touchend'].map((event) => this.button.on(event, this.handleOpenOptions));
  }

  initOptionsEvents() {
    this.handleKeyDownOption = this.handleKeyDownOption.bind(this);

    this.options.map((option) => option.on('keydown', this.handleKeyDownOption));
  }

  initDocumentEvents() {
    this.handleClickOutsideOfMenu = this.handleClickOutsideOfMenu.bind(this);

    ['click', 'touchend'].map((event) => $lp(document).on(event, this.handleClickOutsideOfMenu));
  }

  destroyButtonEvents() {
    ['click', 'touchend'].map((event) => this.button.off(event, this.handleOpenOptions));
  }

  destroyOptionsEvents() {
    this.options.map((option) => option.off('keydown', this.handleKeyDownOption));
  }
}

class Transition {
  /**
   * @constructor
   * @param {Object} el
   */
  constructor(el) {
    this.el = el.selector.nativeElement;
    this.transition = el.transition || TRANSITIONS.scaleUp('center');

    if (!(this.el instanceof Element)) throw new Error(el + ' is not a valid Element');
    this.dispatcher = new EventEmitter();

    this.classes = {
      active: 'lp-enter',
    };

    this.initTransition();
  }

  enter() {
    $lp(this.el).addClass(this.classes.active);
    $lp(this.el).addClass(transition(this.transition).enter);

    _lp.delay(0).then(() => {
      $lp(this.el).addClass(transition(this.transition).enterActive);
    });

    _lp.delay(this.transitionDuration).then(() => {
      this.entered = true;
      this.dispatcher.emit('transition:enter');
    });
  }

  exit() {
    $lp(this.el).addClass(transition(this.transition).exit);
    $lp(this.el).removeClass(transition(this.transition).enter);

    _lp.delay(0).then(() => {
      $lp(this.el).addClass(transition(this.transition).exitActive);
      $lp(this.el).removeClass(transition(this.transition).enterActive);
    });

    _lp.delay(this.transitionDuration).then(() => {
      $lp(this.el).removeClass(transition(this.transition).exit);
      $lp(this.el).removeClass(transition(this.transition).exitActive);

      this.entered = false;
      this.dispatcher.emit('transition:exit');
    });
  }

  /**
   *
   * @returns null
   */
  toggle() {
    if (this.entered) {
      this.exit();
      this.dispatcher.emit('transition:exit');

      return;
    }

    this.enter();
    this.dispatcher.emit('transition:enter');
  }

  initTransition() {
    this.transitionDuration = +getComputedStyle(document.documentElement)
      .getPropertyValue('--lp-transition-duration')
      .replace(/\D/g, '');
  }
}

class Card {
  /**
   * @constructor
   * @param {Object} el
   */
  constructor(el) {
    this.el = el.selector.nativeElement;

    if (!(this.el instanceof Element)) throw new Error(el + ' is not a valid Element');
    this.dispatcher = new EventEmitter();

    this.targets = {
      popover: 'popover',
      button: 'button',
      options: 'options',
    };

    this.keys = {
      arrowDown: 'ArrowDown',
      arrowUp: 'ArrowUp',
      enter: 'Enter',
      escape: 'Escape',
    };

    this.classes = {
      selected: 'lp-selected',
    };

    this.init();
  }

  init() {
    this.popover = new Transition({
      selector: element('card', this.el, 'popover', this.targets),
      transition: TRANSITIONS.scaleUp('top-right'),
    });
    this.popoverWrapper = $lp(this.popover.el).parent();
    $lp(this.popover.el).remove();

    this.button = element('card', this.el, 'button', this.targets);

    this.initEvents();
    this.initDispatcher();

    this.dispatcher.emit('card:init');
  }

  initEvents() {
    this.initOpenerEvents();
    this.initDocumentEvents();

    this.dispatcher.emit('card:init-events');
  }

  initDispatcher() {
    this.dispatcher.on('options:open', () => {
      this.setOptions();
      this.initOptionsEvents();
      this.setFocusedOption();
    });

    ['option:select', 'option:focus'].map((event) => {
      this.dispatcher.on(event, () => {
        this.setFocusedOption();
      });
    });

    this.dispatcher.on('options:close', () => {
      this.destroyOptionsEvents();
    });

    this.dispatcher.on('option:select', () => {
      this.popover.dispatcher.on('transition:exit', () => {
        this.button.focus();
      });
    });
  }

  setOptions() {
    this.list = element('card', this.el, 'options', this.targets);
    this.options = this.list.findAll('li');
    this.list.attr('role', 'listbox').attr('tabindex', -1);
    this.activeOptionIndex = this.activeOptionIndex || 0;

    this.options.forEach((option, id) => {
      option
        .attr('data-lp-option', `option-${id}`)
        .attr('data-lp-value', option.find('span').text())
        .attr('aria-disabled', false)
        .attr('aria-selected', false)
        .attr('tabindex', -1)
        .attr('role', 'option');
    });

    this.dispatcher.emit('options:set');
  }

  setFocusedOption() {
    this.options[this.activeOptionIndex].focus();
  }

  handleOpenOptions() {
    this.optionsListOpened = true;
    this.popover.enter();
    this.popoverWrapper.append(this.popover.el);
    $lp(this.popover.el).attr('aria-hidden', false);

    this.dispatcher.emit('options:open');
  }

  handleCloseOptions() {
    this.optionsListOpened = false;
    this.popover.exit();
    this.popover.dispatcher.on('transition:exit', () => $lp(this.popover.el).attr('aria-hidden', false).remove());

    this.dispatcher.emit('options:close');
  }

  handleSelectOption() {
    this.options.forEach((option) =>
      option.removeClass(this.classes.selected).attr('aria-selected', false).attr('tabindex', -1),
    );

    this.options[this.activeOptionIndex]
      .addClass(this.classes.selected)
      .attr('aria-selected', true)
      .attr('tabindex', 0);
    this.handleCloseOptions();
    this.dispatcher.emit('option:select');
  }

  /**
   *
   * @param {KeyboardEvent} e
   * @param {DomManipulator} option
   */
  handleKeyDownOption(e) {
    e.preventDefault();

    switch (e.key) {
      case this.keys.arrowDown:
        ++this.activeOptionIndex;
        if (this.activeOptionIndex >= this.options.length) this.activeOptionIndex = 0;

        this.dispatcher.emit('option:focus');
        break;

      case this.keys.arrowUp:
        if (!this.activeOptionIndex) this.activeOptionIndex = this.options.length;
        this.activeOptionIndex--;

        this.dispatcher.emit('option:focus');
        break;
    }
  }

  /**
   *
   * @param {MouseEvent | TouchEvent} e
   */
  handleClickOutsideOfMenu(e) {
    const { target } = e;

    if (this.optionsListOpened && !this.el.contains(target)) this.handleCloseOptions();
  }

  initOpenerEvents() {
    this.handleOpenOptions = this.handleOpenOptions.bind(this);

    ['click', 'touchend'].map((event) => this.button.on(event, this.handleOpenOptions));
  }

  initOptionsEvents() {
    this.handleKeyDownOption = this.handleKeyDownOption.bind(this);

    this.options.map((option) => option.on('keydown', this.handleKeyDownOption));
  }

  initDocumentEvents() {
    this.handleClickOutsideOfMenu = this.handleClickOutsideOfMenu.bind(this);

    ['click', 'touchend'].map((event) => $lp(document).on(event, this.handleClickOutsideOfMenu));
  }

  destroyOpenerEvents() {
    ['click', 'touchend'].map((event) => this.button.off(event, this.handleOpenOptions));
  }

  destroyOptionsEvents() {
    this.options.map((option) => option.off('keydown', this.handleKeyDownOption));
  }

  destroyDocumentEvents() {
    ['click', 'touchend'].map((event) => $lp(document).off(event, this.handleClickOutsideOfMenu));
  }
}

class Notifications {
  /**
   * @constructor
   * @param {Object} el
   */
  constructor(el) {
    this.el = el.selector.nativeElement;

    if (!(this.el instanceof Element)) throw new Error(el + ' is not a valid Element');
    this.dispatcher = new EventEmitter();

    this.targets = {
      button: 'button',
      popover: 'popover',
    };

    this.init();
  }

  init() {
    this.popover = new Transition({
      selector: element('notifications', this.el, 'popover', this.targets),
      transition: TRANSITIONS.scaleUp('top'),
    });
    this.popoverWrapper = $lp(this.popover.el).parent();
    $lp(this.popover.el).remove();
    this.button = element('notifications', this.el, 'button', this.targets);

    this.initDispatcher();
    this.initEvents();

    this.dispatcher.emit('notifications:init');
  }

  initEvents() {
    this.initOpenerEvents();
    this.initDocumentEvents();

    this.dispatcher.emit('notifications:init-events');
  }

  initDispatcher() {
    this.dispatcher.on('notifications:open', () => {
      ['click', 'touchend'].map((event) => this.button.off(event, this.handleOpenNotifications));
      ['click', 'touchend'].map((event) => this.button.on(event, this.handleCloseNotifications));
    });

    this.dispatcher.on('notifications:close', () => {
      ['click', 'touchend'].map((event) => this.button.off(event, this.handleCloseNotifications));
      ['click', 'touchend'].map((event) => this.button.on(event, this.handleOpenNotifications));
    });
  }

  handleOpenNotifications() {
    this.notificationsOpened = true;

    this.popover.enter();
    this.popoverWrapper.append(this.popover.el);
    $lp(this.popover.el).attr('aria-hidden', false);

    this.dispatcher.emit('notifications:open');

    this.onOpen && this.onOpen();
  }

  handleCloseNotifications() {
    this.notificationsOpened = false;

    this.popover.exit();
    this.popover.dispatcher.on('transition:exit', () => $lp(this.popover.el).attr('aria-hidden', false).remove());

    this.dispatcher.emit('notifications:close');
  }

  handleClickOutsideOfNotifications(e) {
    const { target } = e;

    if (this.notificationsOpened && !this.el.contains(target)) this.handleCloseNotifications();
  }

  initOpenerEvents() {
    this.handleOpenNotifications = this.handleOpenNotifications.bind(this);
    this.handleCloseNotifications = this.handleCloseNotifications.bind(this);

    ['click', 'touchend'].map((event) => this.button.on(event, this.handleOpenNotifications));
  }

  initDocumentEvents() {
    this.handleClickOutsideOfNotifications = this.handleClickOutsideOfNotifications.bind(this);

    ['click', 'touchend'].map((event) => $lp(document).on(event, this.handleClickOutsideOfNotifications));
  }

  destroyOpenerEvents() {
    ['click', 'touchend'].map((event) => this.button.off(event, this.handleOpenNotifications));
  }

  destroyDocumentEvents() {
    ['click', 'touchend'].map((event) => $lp(document).off(event, this.handleClickOutsideOfNotifications));
  }
}
