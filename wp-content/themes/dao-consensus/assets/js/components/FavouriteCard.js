class FavouriteCard extends Card {
    /**
     * @constructor
     * @param {Object} el
     */
    constructor(el) {
      super(el);
    }
  
    init() {
      super.init();
  
      this.deleteModal = new Modal({ selector: $lp(this.el).find(`[data-lp-card="delete-from-favourites-modal"]`) });
    }
  
    setOptions() {
      super.setOptions();
  
      const [deleteButton] = this.list.children();
      this.deleteButton = deleteButton;
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
              this.handleDeleteFromFavourite();
              break;
          }
  
          break;
      }
    }
  
    handleDeleteFromFavourite(e) {
      this.activeOptionIndex = 0;
      this.handleSelectOption();
  
      this.deleteModal.handleOpen(this.el);
    }
  
    initOptionsEvents() {
      super.initOptionsEvents();
      this.handleDeleteFromFavourite = this.handleDeleteFromFavourite.bind(this);
  
      ['click', 'touchend'].map((event) => $lp(this.deleteButton).on(event, this.handleDeleteFromFavourite));
    }
  
    destroyOptionsEvents() {
      super.destroyOptionsEvents();
  
      ['click', 'touchend'].map((event) => $lp(this.deleteButton).off(event, this.handleDeleteFromFavourite));
    }
  }
  