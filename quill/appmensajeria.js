// #################################################################################
//                                                LOWER TABS - DELTA, TEXT AND HTML
// #################################################################################
/** @class Abstract class representing a tool for a Quill Editor toolbar. */
class QuillToolbarItem {
    constructor(options) {
        const me = this
        me.options = options

        me.qlFormatsEl = document.createElement("span")
        me.qlFormatsEl.className = "ql-formats"
    }
    /**
     * Attaches this tool to the given Quill Editor instance.
     *
     * @param {Quill} quill - The Quill Editor instance that this tool should get added to.
     */
    attach(quill) {
        const me = this
        me.quill = quill
        me.toolbar = quill.getModule('toolbar')
        me.toolbarEl = me.toolbar.container
        me.toolbarEl.appendChild(me.qlFormatsEl)
    }
    /**
     * Detaches this tool from the given Quill Editor instance.
     *
     * @param {Quill} quill - The Quill Editor instance that this tool should get added to.
     */
    detach(quill) {
        const me = this
        me.toolbarEl.removeChild(me.qlFormatsEl)
    }
    /**
     * Calculate the width of text.
     *
     * @param {string} text - The text of which the length should be calculated.
     * @param {string} [font="500 14px 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif"] - The font css that shuold be applied to the text before calculating the width.
     */
    _getTextWidth(text, font="500 14px 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif") {
        const canvas = this._getTextWidth.canvas || (this._getTextWidth.canvas = document.createElement("canvas"))
        const context = canvas.getContext("2d")
        context.font = font
        const metrics = context.measureText(text)
        return metrics.width
    }
    /**
     * Add a global css rule to the document.
     *
     * @param {string} cssRule - CSS rules
     */
    _addCssRule(cssRule) {
        const style = document.createElement("style");
        document.head.appendChild(style);
        style.sheet.insertRule(cssRule, 0)
    }
    /**
     * Generate a random ID.
     *
     * @returns {string} random 10 digit ID
     */
    _generateId() {
        return Math.random().toString().substr(2, 10)
    }
}


/** @class Class representing a dropdown tool for a Quill Editor toolbar. */
class QuillToolbarDropDown extends QuillToolbarItem {
    /**
     * Creates an instance of QuillToolbarDropDown.
     *
     * @constructor
     * @param {object} [options] - The options/settings for this QuillToolbarDropDown.
     * @param {string} [options.id=`dropdown-${random10digitNumber}`] - The id of the quill tool.
     * @param {string} [options.label=""] - The default label that is being displayed before making a selection.
     * @param {boolean} [options.rememberSelection=true] - Automatically change the label to the current selection.
     * @param {object} [options.items={}] - The default items this dropdown will have. Needs to be a key-value-object (key=visible label; value=actual value).
     */
    constructor(options) {
        super(options)
        const me = this

        me.id = me.options.id || `dropdown-${me._generateId()}`

        const qlPicker = document.createElement("span")
        qlPicker.className = `ql-${me.id} ql-picker`
        me.qlFormatsEl.appendChild(qlPicker)

        const qlPickerLabel = document.createElement("span")
        qlPickerLabel.className = "ql-picker-label"
        qlPicker.appendChild(qlPickerLabel)
        qlPickerLabel.addEventListener('click', function(e) {
            qlPicker.classList.toggle('ql-expanded')

        })
        window.addEventListener('click', function(e){
            if (!qlPicker.contains(e.target)){
                qlPicker.classList.remove('ql-expanded')
            }
        })

        const qlPickerOptions = document.createElement("span")
        qlPickerOptions.className = "ql-picker-options"
        qlPicker.appendChild(qlPickerOptions)

        me.dropDownEl = qlPicker
        me.dropDownPickerEl = me.dropDownEl.querySelector('.ql-picker-options')
        me.dropDownPickerLabelEl = me.dropDownEl.querySelector('.ql-picker-label')
        me.dropDownPickerLabelEl.innerHTML = `<svg viewBox="0 0 18 18"> <polygon class="ql-stroke" points="7 11 9 13 11 11 7 11"></polygon> <polygon class="ql-stroke" points="7 7 9 5 11 7 7 7"></polygon> </svg>`

        me.setLabel(me.options.label || "")
        me.setItems(me.options.items || {})

        me._addCssRule(`
            .ql-snow .ql-picker.ql-${me.id} .ql-picker-label::before, .ql-${me.id} .ql-picker.ql-size .ql-picker-item::before {
                content: attr(data-label);
            }
        `)
    }
    /**
     * Set the items for this dropdown tool.
     *
     * @param {object} items - Needs to be a key-value-object (key=visible label; value=actual value).
     */
    setItems(items) {
        const me = this
        for (const [label,value] of Object.entries(items)) {
            const newItemEl = document.createElement("span")
            newItemEl.className = "ql-picker-item"
            newItemEl.innerHTML = label
            newItemEl.setAttribute('data-value', value)
            newItemEl.onclick = function(e) {
                me.dropDownEl.classList.remove('ql-expanded')
                if (me.options.rememberSelection)
                    me.setLabel(label)
                if (me.onSelect)
                    me.onSelect(label, value, me.quill)
            }
            me.dropDownPickerEl.appendChild(newItemEl)
        }
    }
    /**
     * Set the label for this dropdown tool and automatically adjust the width to fit the label.
     *
     * @param {String} newLabel - The new label that should be set.
     */
    setLabel(newLabel) {
        const me = this
        const requiredWidth = `${me._getTextWidth(newLabel)+30}px`
        me.dropDownPickerLabelEl.style.width = requiredWidth
        me.dropDownPickerLabelEl.setAttribute('data-label', newLabel)
    }
    /**
     * A callback that gets called automatically when the dropdown selection changes. This callback is expected to be overwritten.
     *
     * @param {string} label - The label of the newly selected item.
     * @param {string} value - The value of the newly selected item.
     * @param {Quill} quill - The quill instance the dropdown tool is attached to.
     */
    onSelect(label, value, quill) {

    }
}


/** @class Class representing a button tool for a Quill Editor toolbar. */
class QuillToolbarButton extends QuillToolbarItem {
    /**
     * Creates an instance of QuillToolbarButton.
     *
     * @constructor
     * @param {object} [options] - The options/settings for this QuillToolbarButton.
     * @param {string} [options.id=`button-${random10digitNumber}`] - The id of the quill tool.
     * @param {string} [options.value] - The default hidden value of the button.
     * @param {string} options.icon - The default icon this button tool will have.
     */
    constructor(options) {
        super(options)
        const me = this

        me.id = me.options.id || `button-${me._generateId()}`

        me.qlButton = document.createElement("button")
        me.qlButton.className = `ql-${me.id}`
        me.setValue(me.options.value)
        me.setIcon(me.options.icon)
        me.qlButton.onclick = function() {
            me.onClick(me.quill)
        }
        me.qlFormatsEl.appendChild(me.qlButton)
    }
    /**
     * Set the icon for this button tool.
     *
     * @param {string} newLabel - The <svg> or <img> html tag to use as an icon. (Make sure it's 18x18 in size.)
     */
    setIcon(imageHtml) {
        const me = this
        me.qlButton.innerHTML = imageHtml
    }
    /**
     * Set the hidden value of this button tool.
     *
     * @param {string} newLabel - The <svg> or <img> html tag to use as an icon. (Make sure it's 18x18 in size.)
     */
    setValue(value) {
        const me = this
        me.qlButton.value = value
    }
    /**
     * Set the hidden value of this button tool.
     *
     * @param {string} newLabel - The <svg> or <img> html tag to use as an icon. (Make sure it's 18x18 in size.)
     */
    getValue() {
        const me = this
        return me.qlButton.value
    }
    /**
     * A callback that gets called automatically when the button is clicked, tapped or triggered witht he keyboard etc. This callback is expected to be overwritten.
     *
     * @param {Quill} quill - The quill instance the dropdown tool is attached to.
     */
    onClick(button, quill) {

    }
}
/** See: https://www.w3schools.com/howto/howto_js_tabs.asp */
function openTab(evt, tabName) {
    // Declare all variables
    var i, tabcontent, tabButtons;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tab-button" and remove the class "active"
    tabButtons = document.getElementsByClassName("tab-button");
    for (i = 0; i < tabButtons.length; i++) {
        tabButtons[i].className = tabButtons[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    let openTabContent = document.getElementById(tabName);
    openTabContent.style.display = "block";
    evt.currentTarget.className += " active";

    updateOpenTab(openTabContent);
}

function updateOpenTab(tabContent) {
    if (!tabContent) {
        if (!(tabContent = getActiveTabContent())) return false;
    }

    // DELTA
    if (tabContent.id === 'tab-content-delta') {
        // Set delta length (#tab-content-delta > p)
        let p = tabContent.children[0];
        p.innerHTML = 'Size: ' + quill.getLength();
        p.style.padding = '4px 6px';

        // Set data (#tab-content-delta > pre > code)
        let code = tabContent.children[1].children[0];
        code.innerHTML = JSON.stringify(getContentDelta(), null, 2);

        hljs.highlightBlock(code);
    }
    // TEXT
    if (tabContent.id === 'tab-content-text') {
        // #tab-contnet-text > pre > code
        let code = tabContent.children[0].children[0];
        code.innerHTML = getContentText();

        hljs.highlightBlock(code);
    }
    // HTML
    if (tabContent.id === 'tab-content-html') {
        // #tab-content-html > pre > code
        let code = tabContent.children[0].children[0];
        code.innerHTML = getContentHTML().replace(/</gi, '&lt;');

        hljs.highlightBlock(code);
    }
}

/** See: https://stackoverflow.com/a/21696585 */
function isTabHidden(el) {
    if (!el) return true;
    return (el.offsetParent === null)
}

function getActiveTabContent() {
    let tabContents = document.querySelectorAll('.tab-content');
    for (let item of tabContents) {
        if (!isTabHidden(item)) {
            return item;
        }
    }
    return false;
}

// #################################################################################
//                                                                UTILITY FUNCTIONS
// #################################################################################

// See: https://stackoverflow.com/a/5085587
function hasClass(element, className) {
    return (' ' + element.className + ' ').indexOf(' ' + className + ' ') > -1;
}

/** See: https://quilljs.com/docs/api/#getcontents */
function getContentDelta() {
    return quill.getContents();
}

/** See: https://github.com/quilljs/quill/issues/2698 */
function getContentText() {
    return quill.getContents().ops.reduce((text, op) => {
        if (typeof op.insert === 'string') {
            return text + op.insert;
        }
        // If it is not a string, the newline character is set, which has
        // a length of only 1 character.
        else {
            return text + '\n';
        }
    }, '');
}

/** See: https://github.com/quilljs/quill/issues/903#issuecomment-243844178 */
function getContentHTML() {
    return quill.root.innerHTML;
}

function extractVideoUrl(url) {
    let match =
      url.match(/^(?:(https?):\/\/)?(?:(?:www|m)\.)?youtube\.com\/watch.*v=([a-zA-Z0-9_-]+)/,) ||
      url.match(/^(?:(https?):\/\/)?(?:(?:www|m)\.)?youtu\.be\/([a-zA-Z0-9_-]+)/);
  
    if (match) {
      return `${match[1] || 'https'}://www.youtube.com/embed/${match[2]}?showinfo=0`;
    }
  
    // eslint-disable-next-line no-cond-assign
    if ((match = url.match(/^(?:(https?):\/\/)?(?:www\.)?vimeo\.com\/(\d+)/))) {
      return `${match[1] || 'https'}://player.vimeo.com/video/${match[2]}/`;
    }
    
    return url;
  }

// #################################################################################
//                                                                          TOOLTIP
// #################################################################################

/** Customizable tooltip, which follows the same design as Quill Editor's native 
  * tooltip. Below are all the configurations that can be made, as well as a 
  * description of what each one represents.
  * 
  * [OPTIONS]
  * format = The format to be worked by tooltip.
  * inputLabel = Left label given to value input.
  * inputLabelClass = Overrides CSS class for value input label styling.
  * inputPlaceholder = Value input placeholder text (<input placeholder="...">).
  * inputClass = Overrides CSS class for value input styling.
  * actionText = Text for action "button" to the right of value input.
  * actionClass = Overrides CSS class for action text (the "button").
  * containerClass = Replaces CSS class for element that contains all others.
  * hideOnTyping = If true, tooltip will be hidden when typing in Quill.
  * hideOnAction = If true, tooltip will be hidden by clicking action text.
  * clearAfterHide = If true, the value input has its text cleared after tooltip is hidden.
  * defaultValue = Defines a default value for value input. If set, clearAfterHide is always false.
  * onAction = Function called when action text is clicked by the user. Setting a value for this property makes the user responsible for the tooltip action.
  * onShow = Function called when tooltip is revealed.
  * onHide = Function called when tooltip is hidden.
  */
class CustomizableTooltip {
    constructor(format , quill , options) {
        // Is everything ok here?
        this.checkState(format , quill);
        this.checkOptions(options);

        // Everything seems ok here...
        this.quill = quill;
        this.format = format;

        // Lets build...
        this.buildInterface();

        var thisTooltip = this;
        this.quill.getModule("toolbar").addHandler(this.format , function(){
            thisTooltip.toolbarButtonPressed();
        });

        this.quill.container.appendChild(this.container);
        this.hide();
    }

    // This prevents a surprise from appearing while using the editor. If a 
    // problem exists, it will appear when the tooltip is being built.
    checkState(format , quill) {
        // Is Quill reference useful?
        if (quill === null || typeof quill !== 'object') 
        throw 'Quill reference was not passed in argument, or is null.';

        // Was the format specified?
        if(!format || format.length <= 0)
        throw 'No format was specified.';

        // Is the format registered?
        if(!Quill.import('formats/' + format))
        throw 'No format "' + format + '" found. Please, be sure to pass a format that is registered within Quill.';
    }

    /** Checks whether properties have been set correctly, or need to be 
      * overwritten if not. */
    checkOptions(options) {
        if (!options || options == null) options = {};

        if (!options.inputLabel || options.inputLabel.length <= 0)
            options.inputLabel = 'Value';
        if (!options.inputLabelClass || options.inputLabelClass.length <= 0)
            options.inputLabelClass = 'rs-tooltip-label';
        if (!options.inputPlaceholer || options.inputPlaceholer.left <= 0)
            options.inputPlaceholer = 'Insert value here...';
        if (!options.inputClass || options.inputClass.length <= 0)
            options.inputClass = 'rs-tooltip-input';
        if (!options.actionText || options.actionText.length <= 0)
            options.actionText = 'Conclude';
        if (!options.actionClass || options.actionClass.length <= 0)
            options.actionClass = 'rs-tooltip-action';
        if (!options.containerClass || options.containerClass.length <= 0)
            options.containerClass = 'rs-tooltip-container';
        if (!options.hideOnTyping)
            options.hideOnTyping = false;
        if (options.clearAfterHide && options.defaultValue && options.defaultValue.length >= 0)
            options.clearAfterHide = false;
        if (!options.hideOnAction || options.hideOnAction == false)
            options.hideOnAction = true;

        this.op = options;
    }

    buildInterface() {
        this.buildContainer();
        this.buildInputLabel();
        this.buildInput();
        this.buildAction();

        // Adds built elements into this tooltip container.
        this.container.appendChild(this.inputLabel);
        this.container.appendChild(this.input);
        this.container.appendChild(this.action);
    }

    buildContainer() {
        var thisTooltip = this;
        var container = document.createElement('DIV');
        container.classList.add(this.op.containerClass);

        // Hide tooltip by clicking outside of it.
        document.body.addEventListener('click', event => {
            // Was it clicked off?
            if (!hasClass(event.target, thisTooltip.op.containerClass)) {
                // Prevents tooltip from ever appearing if its button is clicked.
                // The button can have several internal elements. You can even 
                // click on the SVG element without clicking the button (the 
                // button icon, or the elements representing the icon). We take
                // this into account with closest, and verify that in addition
                // to the button, some element of it has been clicked.
                if (hasClass(event.target , 'ql-' + thisTooltip.format) ||
                    event.target.closest('.ql-' + thisTooltip.format)) {
                    return;
                }

                thisTooltip.hide();
            }
        });

        // Prevents tooltip from being hidden if its content is clicked.
        container.addEventListener('click', event => {
            event.stopPropagation();
        });

        // Hide tooltip when typing text in the editor.
        if (this.op.hideOnTyping) {
            this.quill.on('text-change', function (delta, oldDelta, source) {
                if (source === 'user') {
                    thisTooltip.hide();
                }
            });
        }

        this.container = container;
    }

    buildInputLabel() {
        var label = document.createElement('SPAN');
        label.innerText = this.op.inputLabel;
        label.classList.add(this.op.inputLabelClass);

        this.inputLabel = label;
    }

    buildInput() {
        var thisTooltip = this;
        var input = document.createElement('INPUT');
        input.type = 'text';
        input.classList.add(this.op.inputClass);
        input.placeholder = this.op.inputPlaceholer;
        input.value = this.op.defaultValue && this.op.defaultValue.length > 0 ?
            this.op.defaultValue :
            '';

        // Has the user added an event of his own to this tooltip? If so, it 
        // will be called as a priority.
        if (this.op.onAction && typeof this.op.onAction === 'function') {
            input.addEventListener('keydown', e => {
                if (e.key === 'Enter') {
                    this.op.onAction(input.value, e);
                    if(thisTooltip.op.hideOnAction) thisTooltip.hide();
                }
            });
        }
        // Otherwise, the tooltip calls the default implementation. It is
        // understood that the user knows how this tooltip works, and has
        // configured it correctly.
        else {
            input.addEventListener('keydown', e => {
                if (e.key === 'Enter') {
                    thisTooltip.insertEmbed(input.value , e);
                    if(thisTooltip.op.hideOnAction) thisTooltip.hide();
                }
            });
        }

        this.input = input;
    }

    buildAction() {
        var thisTooltip = this;
        var linkAction = document.createElement('a');
        linkAction.innerText = this.op.actionText;
        linkAction.classList.add(this.op.actionClass);

        // Has the user added an event of his own to this tooltip? If so, it 
        // will be called as a priority.
        if (this.op.onAction && typeof this.op.onAction === 'function') {
            linkAction.addEventListener('click', e => {
                this.op.onAction(thisTooltip.input.value, e);
                if(thisTooltip.op.hideOnAction) thisTooltip.hide();
            });
        }
        // Otherwise, the tooltip calls the default implementation. It is
        // understood that the user knows how this tooltip works, and has
        // configured it correctly.
        else {
            linkAction.addEventListener('click', e => {
                thisTooltip.insertEmbed(thisTooltip.input.value , e);
                if(thisTooltip.op.hideOnAction) thisTooltip.hide();
            });
        }

        this.action = linkAction;
    }

    toolbarButtonPressed() {
        if (this.isVisible()) {
            this.hide();
            return;
        }

        this.show();
    }

    // Created for the convenience of client code. Not necessarily this needs to be utilized.
    setInputLabel(label) {
        if (!label || label.length <= 0) return;
        this.inputLabel.innerText = label;
    }

    // Created for the convenience of client code. Not necessarily this needs to be utilized.
    setActionLabel(label) {
        if (!label || label.length <= 0) return;
        this.action.innerText = label;
    }

    // Created for the convenience of client code. Not necessarily this needs to be utilized.
    setInputValue(value) {
        if (!value || value.length <= 0) return;
        this.input.value = value;
    }

    // Created for the convenience of client code. Not necessarily this needs to be utilized.
    setInputPlaceholder(placeholder) {
        if (!placeholder || placeholder.length <= 0) return;
        this.input.placeholder = placeholder;
    }

    // Created for the convenience of client code. Not necessarily this needs to be utilized.
    getInputValue() {
        return this.input.value;
    }

    show() {
        if(this.isVisible()) return;

        this.container.classList.remove('ql-hidden');
        this.updatePosition();

        if (this.op.onShow && typeof onShow === 'function') this.op.onShow();
    }

    hide() {
        if(!this.isVisible()) return;

        this.container.classList.add('ql-hidden');

        if(!this.op.defaultValue && this.op.clearAfterHide) this.input.value = '';
        if (this.op.onHide && typeof onHide === 'function') this.op.onHide();
    }

    isVisible() {
        return !hasClass(this.container, 'ql-hidden');
    }

    updatePosition() {
        // quill.getBounds return a rectangle based on editor caret position. 
        // This is where we can locate where the window will appear. The idea 
        // here is to leave the horizontal center of the tooltip aligned with 
        // the editor caret (range index).
        let range = this.quill.getSelection();
        const bounds = this.quill.getBounds(range.index);

        // Tooltip left edge X.
        let x = bounds.left - this.container.offsetWidth / 2;
        let editorContainer = document.querySelector('.ql-container');

        // See: http://javascript.info/coordinates
        let leftBorderLimit = editorContainer.getBoundingClientRect().left;
        let rightBorderLimit = editorContainer.getBoundingClientRect().right;

        // Corrects left edge.
        if ((x * -1) > leftBorderLimit) {
            x = x + ((x * -1) - leftBorderLimit);
        }

        // Tooltip right edge X.
        let widthX = x + this.container.offsetWidth + 2;

        // Corrects right edge.
        if (widthX > rightBorderLimit) {
            x = x - (widthX - rightBorderLimit);
        }
        
        this.container.style.top = 
            bounds.top +        // Y from upper left edge.
            bounds.height +     // Caret Height (one line).
            10 +                // One more small space to not get too on top.
            'px';
        this.container.style.left = x + 'px';
    }

    insertEmbed(value , e){
        if(!value || value.length <= 0) return;

        var range = this.quill.getSelection();
        if(!range) range = {
            index: getContentText().length - 1,
            length: 0
        };

        // Unfortunately this line of code needs to be here. I don't understand
        // why it is necessary to format the URL when adding video as embed, as
        // it should already do this automatically ...
        if(this.format === 'video'){
            value = extractVideoUrl(value);
        }

        this.quill.insertEmbed(
            range.index , 
            this.format , 
            value);
    }
}

// #################################################################################
//                                                                             MAIN
// #################################################################################

$(document).ready(function () {
    // No need to create a handler.
    
    var toolbarOptions = {
        
        container:
        
            [['bold', 'italic', 'underline', 'strike'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }],
            [{ 'color': ['red','blue','green','black','inherit','grey'] }],          // dropdown with defaults from theme
            [{ 'size': ['small', false, 'large', 'huge'] }],
            ['link'],
            ['clean']]
    };

    var quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            'toolbar': toolbarOptions
        }
    });
    const dropDownItems = {
        'Nombre Usuario': '*|Nombre Usuario|* ',
        'Empresa Usuario': '*|Empresa Usuario|* ',
        'Correo Usuario': '*|Correo Usuario|* ',
        'Nombre Contraparte': '*|Nombre Contraparte|* ',
        'Empresa Contraparte': '*|Empresa Contraparte|* '
    }
    
    const myDropDown = new QuillToolbarDropDown({
        label: "Variables",
        rememberSelection: false
    })
    
    myDropDown.setItems(dropDownItems)
    
    myDropDown.onSelect = function(label, value, quill) {
        // Do whatever you want with the new dropdown selection here
    
        // For example, insert the value of the dropdown selection:
        const { index, length } = quill.selection.savedRange
        quill.deleteText(index, length)
        quill.insertText(index, value)
        quill.setSelection(index + value.length)
    }
    
    myDropDown.attach(quill)
    // All utility methods that use this global variable will only work with
    // the specific newly created instance of Quill. If other Quill instances
    // are created, they will continue to work only for the only instance
    // already specified. In order for utility methods to work with the new
    // instances, changes would need to be made.
    window.quill = quill;

    // Just pass the format name with the Quill reference. Of course, for 
    // better use of this tool, the user can perform additional settings.
    // All configurations that can be made are commented in the class 
    // documentation (up/above class declaration).
    var tooltip = new CustomizableTooltip('image' , quill , {
        inputLabel: 'Image:' ,
        inputPlaceholer: 'Image URL...' , 
        actionText: 'Insert',
        hideOnTyping: true,
        clearAfterHide: true
    });

    // Open default tab. See: https://www.w3schools.com/howto/howto_js_tabs.asp
    const defualtTab = document.getElementById("default-tab-button");
    defualtTab.click();

    // Quill content alteration.
    quill.on('text-change', function (delta, oldDelta, source) {
        updateOpenTab();
    });
});