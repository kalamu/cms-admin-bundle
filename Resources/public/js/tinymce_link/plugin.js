/**
 * Plugin Link pour kalamu
 */

$.widget("kalamu.tinyMceLinkPicker", {
    options: {
        api_url: null,
        text: '',
        url: '',
        title: '',
        class: '',
        id: '',
        target: ''
    },

    /**
     * Création du Widget
     * @returns {undefined}
     */
    _create: function() {
        $.ajax({
            url: this.options.api_url,
            context: this,
            success: function(datas){
                this.element.append( $('<div class="row mn"></div>').html(datas) );
                
                this._on( this.element.find('#cancel-link'), {
                    click: 'close'
                });
                
                this._on( this.element.find('#add-link'), {
                    click: 'add'
                });
                
                this._refresh();
            }
        });
    },

    _refresh: function() {
        
        this.element.find('#link-text').val(this.options.text);
        this.element.find('#link-url').val(this.options.url);
        this.element.find('#link-title').val(this.options.title);
        this.element.find('#link-class').val(this.options.class);
        this.element.find('#link-id').val(this.options.id);
        
        if(this.options.target.length){
            this.element.find('#link-target option[value="'+this.options.target+'"]').prop('selected', true);
        }else{
            this.element.find('#link-target option').prop('selected', false);
        }

    },
    
    add: function(){
        this.element.trigger('link_picker.add', {
            text: this.element.find('#link-text').val(),
            url: this.element.find('#link-url').val(),
            title: this.element.find('#link-title').val(),
            class: this.element.find('#link-class').val(),
            id: this.element.find('#link-id').val(),
            target: this.element.find('#link-target').val()
        });
    },

    close: function(){
        this.element.trigger('link_picker.close');
    },

    _destroy: function() {
        this.element.remove();
    },
    _setOptions: function() {
        this._superApply(arguments);
        this._refresh();
    },
    _setOption: function(key, value) {
        this._super(key, value);
    }
});

tinymce.PluginManager.add('kalamuLink', function (editor) {
    
    var openLinkModal = function (){
        if(typeof link_api_url === 'undefined'){
            alert("Vous devez configurer l'adresse de l'API en définissant la varible 'link_api_url'.");
            return false;
        }
        
        function isOnlyTextSelected() {
            var html = editor.selection.getContent();
            // Partial html and not a fully selected anchor element
            if (/</.test(html) && (!/^<a [^>]+>[^<]+<\/a>$/.test(html) || html.indexOf('href=') == -1)) {
                return false;
            }
            return true;
        }
        
        restaureModal = function(e){
            $('.modal:visible .modal-body:eq(1)').remove();
            $('.modal:visible .modal-body').css({overflow: 'visible', height: 'auto', padding: '15px'});
        };
        
        selectedElm = editor.selection.getNode();
        anchorElm = editor.dom.getParent(selectedElm, 'a[href]');
        initialText = anchorElm ? (anchorElm.innerText || anchorElm.textContent) : editor.selection.getContent({format: 'text'});

        container = $('<div class="modal-body">').tinyMceLinkPicker({
            api_url: link_api_url,
            text: initialText,
            url: anchorElm ? editor.dom.getAttrib(anchorElm, 'href') : '',
            title: editor.dom.getAttrib(anchorElm, 'title'),
            class: editor.dom.getAttrib(anchorElm, 'class'),
            id: editor.dom.getAttrib(anchorElm, 'id'),
            target: editor.dom.getAttrib(anchorElm, 'target')
        });
        origin = $('.modal:visible .modal-body');
        origin.css({overflow: 'hidden', height: 0}).after(container);
        $('.modal:visible .modal-body:eq(0)').css('padding', '0');
        
        container.on('link_picker.close link_picker.add', restaureModal);
        container.parents('.modal').on('hide.bs.modal', restaureModal);
        
        container.on('link_picker.add', $.proxy(function(e, data){
            
            var linkAttrs = {
                href: data.url,
                target: data.target ? data.target : null,
                class: data.class ? data.class : null,
                id: data.id ? data.id : null,
                title: data.title ? data.title : null
            };
            
            anchorElm = editor.dom.getParent(editor.selection.getNode(), 'a[href]');
            
            if (anchorElm) {
                editor.focus();

                if (isOnlyTextSelected() && data.text != initialText) {
                    if ("innerText" in anchorElm) {
                        anchorElm.innerText = data.text;
                    } else {
                        anchorElm.textContent = data.text;
                    }
                }

                editor.dom.setAttribs(anchorElm, linkAttrs);
                editor.selection.select(anchorElm);
                editor.undoManager.add();
            } else {
                if (isOnlyTextSelected()) {
                    editor.insertContent(editor.dom.createHTML('a', linkAttrs, editor.dom.encode(data.text)));
                } else {
                    editor.execCommand('mceInsertLink', false, linkAttrs);
                }
            }
            
        }, editor));
        
    };
    

    editor.addButton('kalamuLink', {
        image: '/bundles/rohocms/image/internal_link.png',
        tooltip: 'Lien contenu interne',
        onclick: openLinkModal,
        stateSelector: 'a[href]'
    });

    editor.addMenuItem('kalamuLink', {
        image: '/bundles/rohocms/image/internal_link.png',
        text: 'Lien contenu interne',
        onclick: openLinkModal,
        stateSelector: 'a[href]',
        context: 'insert',
        prependToContext: true
    });
});
