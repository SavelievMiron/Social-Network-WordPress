class Modal {
  /**
   * @constructor
   * @param {Object} el
   */
  constructor(el) {
    this.el = el.selector.nativeElement;

    if (!(this.el instanceof Element)) throw new Error(el + ' is not a valid Element');
    this.dispatcher = new EventEmitter();

    this.targets = {
      close: 'close',
      cancel: 'cancel',
      content: 'content',
    };

    this.initModal();
  }

  initModal() {
    this.modalWrapper = $lp(this.el).parent();
    $lp(this.el).attr('tabindex', -1).attr('role', 'dialog').attr('aria-modal', true).remove();

    this.content = new Transition({ selector: element('modal', this.el, 'content', this.targets) });
    this.close = element('modal', this.el, 'close', this.targets);
    this.cancel = element('modal', this.el, 'cancel', this.targets);

    this.initEvents();

    this.dispatcher.emit('modal:init');
  }

  initEvents() {
    this.initCloseButtonEvents();

    this.dispatcher.emit('modal:init-events');
  }

  handleOpen() {
    this.modalOpened = true;
    this.content.enter();
    this.modalWrapper.append(this.el);
    $lp(this.el).attr('aria-hidden', false);
    document.body.style.cssText = `
      overflow: hidden;
      padding-right: 17px
    `;

    this.dispatcher.emit('modal:open');
  }

  handleClose() {
    this.modalOpened = true;
    this.content.exit();
    this.content.dispatcher.on('transition:exit', () => {
      $lp(this.el).attr('aria-hidden', true).remove();

      ['overflow', 'padding-right'].map((prop) => document.body.style.removeProperty(prop));
    });

    this.onClose && this.onClose(this.el);
    this.dispatcher.emit('modal:close');
  }

  initCloseButtonEvents() {
    this.handleClose = this.handleClose.bind(this);

    ['click', 'touchend'].map((event) => {
      $lp(this.el)
        .findAll('[data-lp-close]')
        .map((item) => item.on(event, this.handleClose));
      this.close.on(event, this.handleClose);
    });
    if (this.cancel.nativeElement) ['click', 'touchend'].map((event) => this.cancel.on(event, this.handleClose));
  }

  destroyCloseButtonEvents() {
    ['click', 'touchend'].map((event) => {
      $lp(this.el)
        .findAll('[data-lp-close]')
        .map((item) => item.off(event, this.handleClose));
      this.close.off(event, this.handleClose);
    });
    if (this.cancel.nativeElement) ['click', 'touchend'].map((event) => this.cancel.off(event, this.handleClose));
  }
}
