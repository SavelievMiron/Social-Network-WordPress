class InProcessCard extends Card {
  /**
   * @constructor
   * @param {Object} el
   */
  constructor(el) {
    super(el);
  }

  init() {
    super.init();

    this.finishModal = new Modal({ selector: $lp(this.el).find(`[data-lp-card="completed-modal"]`) });
    this.notFinishModal = new Modal({ selector: $lp(this.el).find(`[data-lp-card="not-completed-modal"]`) });
  }

  setOptions() {
    super.setOptions();

    const [finishButton, notFinishButton] = this.list.children();
    this.finishButton = finishButton;
    this.notFinishButton = notFinishButton;
  }

  /**
   *
   * @param {KeyboardEvent} e
   * @param {DomManipulator} option
   */
  handleKeyDownOption(e) {
    super.handleKeyDownOption(e);
    e.preventDefault();

    switch (e.key) {
      case this.keys.enter:
        switch (this.activeOptionIndex) {
          case 0:
            this.handleFinishCard();
            break;
          case 1:
            this.handleNotFinishCard();
            break;
        }

        break;
    }
  }

  handleFinishCard(e) {
    this.activeOptionIndex = 0;
    this.handleSelectOption();

    this.finishModal.handleOpen(this.el);
  }

  handleNotFinishCard(e) {
    this.activeOptionIndex = 1;
    this.handleSelectOption();

    this.notFinishModal.handleOpen(this.el);
  }

  initOptionsEvents() {
    super.initOptionsEvents();
    this.handleFinishCard = this.handleFinishCard.bind(this);
    this.handleNotFinishCard = this.handleNotFinishCard.bind(this);

    ['click', 'touchend'].map((event) => $lp(this.finishButton).on(event, this.handleFinishCard));
    ['click', 'touchend'].map((event) => $lp(this.notFinishButton).on(event, this.handleNotFinishCard));
  }

  destroyOptionsEvents() {
    super.destroyOptionsEvents();

    ['click', 'touchend'].map((event) => $lp(this.finishButton).off(event, this.handleFinishCard));
    ['click', 'touchend'].map((event) => $lp(this.notFinishButton).off(event, this.handleNotFinishCard));
  }
}
