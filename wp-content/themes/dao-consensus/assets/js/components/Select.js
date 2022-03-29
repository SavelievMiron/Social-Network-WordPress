class Select {
  /**
   *
   * @param {Object} el
   */
  constructor(el) {
    this.el = el.selector.nativeElement;
    this.isMulti = el.isMulti || false;
    this.searchable = el.searchable || false;

    if (!(this.el instanceof Element)) throw new Error(el + 'is not a valid Element');
    this.dispatcher = new EventEmitter();

    this.keys = {
      arrowDown: 'ArrowDown',
      arrowUp: 'ArrowUp',
      enter: 'Enter',
      escape: 'Escape',
    };

    this.classes = {
      selected: 'lp-selected',
    };

    this.targets = {
      button: 'button',
      clear: 'clear',
      value: 'value',
      input: 'input',
      options: 'options',
      popover: 'popover',
      search: 'search',
      textfield: 'textfield',
    };

    this.initSelect();
  }

  initSelect() {
    this.popover = new Transition({
      selector: element('select', this.el, 'popover', this.targets),
      transition: TRANSITIONS.scaleUp('top-right'),
    });
    this.popoverWrapper = $lp(this.popover.el).parent();

    this.textfield = new TextField({ selector: element('select', this.el, 'textfield', this.targets) });
    ['button', 'clear', 'value', 'input'].map(
      (field) => (this[field] = element('select', this.el, field, this.targets)),
    );
    this.input.attr('aria-hidden', true).attr('tabindex', -1);
    this.setOptions();
    $lp(this.popover.el).remove();

    this.initEvents();
    this.initDispatcher();

    this.dispatcher.emit('select:init');
  }

  initEvents() {
    this.initOpenerEvents();
    this.initDocumentEvents();

    this.dispatcher.emit('select:init-events');
  }

  initDispatcher() {
    this.dispatcher.on('options:open', () => {
      this.searchfield = new TextField({ selector: element('select', this.el, 'search', this.targets) });
      if (!this.searchable) $lp(this.searchfield.el).attr('aria-hidden', true).attr('tabindex', -1);
      else this.initSearchEvents();

      this.initOptionsEvents();
      this.setFocusedOption();
    });

    ['option:select', 'option:focus'].map((event) => {
      this.dispatcher.on(event, () => {
        this.setFocusedOption();
      });
    });

    this.dispatcher.on('options:close', () => {
      this.destroySearchEvents();
      this.destroyOptionsEvents();
      this.searchfield.destroy();

      this.popover.dispatcher.on('transition:exit', () => {
        $lp(this.popover.el).attr('aria-hidden', false).remove();
        this.button.focus();
      });
    });
  }

  setOptions() {
    this.list = element('select', this.el, 'options', this.targets);
    this.list.attr('role', 'listbox').attr('tabindex', -1);
    this.options = this.list.findAll('li');
    this.filteredOptions = this.options;
    this.selectedOptions = [];

    this.filteredOptions.forEach((option, id) => {
      const selected = option.attr('data-lp-selected');

      option
        .attr('data-lp-option', id)
        .attr('data-lp-selected', selected ? true : false)
        .attr('aria-selected', selected ? true : false)
        .attr('aria-disabled', false)
        .attr('tabindex', selected ? 0 : -1)
        .attr('role', 'option');

      if (selected) {
        const text = option.find('span').text(),
          value = option.attr('data-lp-value');

        if (this.isMulti) {
          this.setActiveOption(option);
          this.selectedOptions.push({ option, id, value, text });
          this.setSelectedOptionsValue();
        } else this.setSelectedOption(id, text);

        this.clear.addClass(this.classes.selected);
      }
    });
    this.activeOptionIndex = this.activeOptionIndex || 0;

    this.dispatcher.emit('options:set');
  }

  setSelectedOption(id, text) {
    this.activeOptionIndex = _lp.isDefined(id) ? id : this.activeOptionIndex;
    const option = this.filteredOptions[this.activeOptionIndex];

    this.setActiveOption(option);
    this.value.text(text);
    this.textfield.value = option.attr('data-lp-value');
  }

  setSelectedOptions(id, option, text) {
    const selected = option.attr('data-lp-selected'),
      value = option.attr('data-lp-value');

    if (selected === 'true') {
      this.setInactiveOption(option);
      this.selectedOptions = this.selectedOptions.filter((option) => option.id !== id);
    } else {
      this.setActiveOption(option);
      this.selectedOptions.push({ option, id, value, text });
    }

    this.setSelectedOptionsValue();
  }

  setSelectedOptionsValue() {
    this.value.text(this.selectedOptions.map((option) => option.text).join(', '));
    this.textfield.value = this.selectedOptions.map((option) => option.value).join(',');
  }

  setActiveOption(option) {
    option
      .addClass(this.classes.selected)
      .attr('data-lp-selected', true)
      .attr('aria-selected', true)
      .attr('tabindex', 0);

    if (this.isMulti) {
      const icon = document.createElement('i');
      $lp(icon).attr('class', 'lp-icon lp-postfix lp-check-flat');
      option.append(icon);
    }
  }

  setInactiveOption(option) {
    option
      .removeClass(this.classes.selected)
      .attr('data-lp-selected', false)
      .attr('aria-selected', false)
      .attr('tabindex', -1);

    if (this.isMulti) option.find('.lp-postfix').remove();
  }

  setFocusedOption() {
    if (!this.filteredOptions.length) return;
    this.filteredOptions[this.activeOptionIndex].focus();
  }

  /**
   *
   * @param {MouseEvent | TouchEvent} e
   */
  handleOpenOptions(e) {
    this.optionsListOpened = true;
    this.popover.enter();
    this.popoverWrapper.append(this.popover.el);
    $lp(this.popover.el).attr('aria-hidden', false);

    this.onOpen && this.onOpen(e);
    this.dispatcher.emit('options:open', e);
  }

  /**
   *
   * @param {MouseEvent | TouchEvent | KeyboardEvent} e
   */
  handleCloseOptions(e) {
    this.optionsListOpened = false;
    this.popover.exit();

    this.onClose && this.onClose(e);
    this.dispatcher.emit('options:close', e);
  }

  /**
   *
   * @param {MouseEvent | TouchEvent} e
   */
  handleSelect(e) {
    const id = +$lp(e.target).attr('data-lp-option'),
      value = $lp(e.target).find('span').text(),
      selectedOption = this.filteredOptions[id];

    this.clear.addClass(this.classes.selected);
    if (this.isMulti) this.handleSelectOptions(id, selectedOption, value);
    else this.handleSelectOption(e, id, selectedOption, value);
  }

  handleClearSelect() {
    this.clear.removeClass(this.classes.selected);
    this.value.text('');
    this.textfield.value = '';
    this.selectedOptions.map(({ option }) => this.setInactiveOption(option));
    this.selectedOptions = [];

    this.onClear && this.onClear();
  }

  /**
   *
   * @param {MouseEvent | TouchEvent} e
   * @param {Number} id
   * @param {any} option
   * @param {String} value
   */
  handleSelectOption(e, id, option, value) {
    this.filteredOptions.forEach((option) => this.setInactiveOption(option));

    this.setSelectedOption(id, value);
    this.handleCloseOptions(e);

    this.dispatcher.emit('option:select', option);
    this.onSelect &&
      this.onSelect({
        option: option.nativeElement,
        id,
        text: value,
        value: option.attr('data-lp-value'),
      });
  }

  /**
   *
   * @param {Number} id
   * @param {any} option
   */
  handleSelectOptions(id, option, value) {
    this.setSelectedOptions(id, option, value);

    this.dispatcher.emit('option:multi-select', this.selectedOptions);
    if (!option.hasClass('lp-selected')) this.onDeselect && this.onDeselect(this.selectedOptions);
    else this.onSelect && this.onSelect(this.selectedOptions);
  }

  /**
   *
   * @param {MouseEvent | TouchEvent} e
   */
  handleClickOutsideOfSelect(e) {
    const { target } = e;

    if (this.optionsListOpened && !this.el.contains(target)) this.handleCloseOptions(e);
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
        if (this.activeOptionIndex >= this.filteredOptions.length) this.activeOptionIndex = 0;

        this.dispatcher.emit('option:focus');
        break;

      case this.keys.arrowUp:
        if (!this.activeOptionIndex) this.activeOptionIndex = this.filteredOptions.length;
        this.activeOptionIndex--;

        this.dispatcher.emit('option:focus');
        break;

      case this.keys.enter:
        this.handleSelect(e);

        break;
    }
  }

  handleSearch(e) {
    this.options.forEach((option) => option.attr('aria-hidden', true));
    this.filteredOptions = this.options
      .filter((option) => option.find('span').text().toLowerCase().includes(e.target.value.toLowerCase()))
      .map((option) => option.attr('aria-hidden', false));

    this.dispatcher.emit('select:search', this.filteredOptions);
  }

  initOpenerEvents() {
    this.handleOpenOptions = this.handleOpenOptions.bind(this);
    this.handleClearSelect = this.handleClearSelect.bind(this);

    ['click', 'touchend'].map((event) => this.value.on(event, this.handleOpenOptions));
    ['click', 'touchend'].map((event) => this.button.on(event, this.handleOpenOptions));
    ['click', 'touchend'].map((event) => this.clear.on(event, this.handleClearSelect));
  }

  initSearchEvents() {
    this.handleSearch = this.handleSearch.bind(this);

    $lp(this.searchfield.el).on('input', this.handleSearch);
  }

  initOptionsEvents() {
    this.handleKeyDownOption = this.handleKeyDownOption.bind(this);
    this.handleSelect = this.handleSelect.bind(this);

    this.options.map((option) => {
      option.on('keydown', this.handleKeyDownOption);
      ['click', 'touchend'].map((event) => option.on(event, this.handleSelect));
    });
  }

  initDocumentEvents() {
    this.handleClickOutsideOfSelect = this.handleClickOutsideOfSelect.bind(this);

    ['click', 'touchend'].map((event) => $lp(document).on(event, this.handleClickOutsideOfSelect));
  }

  destroyOpenerEvents() {
    ['click', 'touchend'].map((event) => this.value.off(event, this.handleOpenOptions));
    ['click', 'touchend'].map((event) => this.button.off(event, this.handleOpenOptions));
    ['click', 'touchend'].map((event) => this.clear.off(event, this.handleClearSelect));
  }

  destroySearchEvents() {
    $lp(this.searchfield.el).off('input', this.handleSearch);
  }

  destroyOptionsEvents() {
    this.options.map((option) => {
      option.off('keydown', this.handleKeyDownOption);
      ['click', 'touchend'].map((event) => option.off(event, this.handleSelect));
    });
  }

  destroyDocumentEvents() {
    ['click', 'touchend'].map((event) => $lp(document).off(event, this.handleClickOutsideOfSelect));
  }

  destroy() {
    this.destroyOpenerEvents();
    this.destroyOptionsEvents();
    this.destroyDocumentEvents();
    this.textfield.destroy();

    this.dispatcher.off('option:select');
    this.dispatcher.off('option:focus');
    this.dispatcher.off('options:open');
    this.dispatcher.off('options:close');
  }
}
