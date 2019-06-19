
/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Widget pour sélectionner des pages du CMS
 */
$.widget("kalamu.cmsLinkSelector", {
    options: {
        picker_api: '',
        editLink: null,
        label: null,
        modal: null,
        embedModal: false,
        required : null,
        modalContent: null
    },

    /**
     * Création du Widget
     * @returns {undefined}
     */
    _create: function() {
        this.element.addClass('cmsLinkSelector');

        this.options.label = $('<div class="btn btn-default">...</div>');
        this.options.editLink = $('<a href="#" class="mr10"><i class="fa fa-external-link"></i> Modifier</a>');
        if(!this.options.required){
            this.options.removeLink = $('<a href="#" class="mr10"><i class="fa fa-trash"></i> Retirer</a>');
        }

        this.element.append('<div class="clearfix"></div>');
        this.element.append(this.options.label);
        this.element.append('<div class="clearfix"></div>');
        this.element.append(this.options.editLink);
        if(!this.options.required){
            this.element.append(this.options.removeLink);
        }

        tmplModalContent = '<div class="modal-header">'
                                +'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
                                +'<h4 class="modal-title">Sélectionner un contenu</h4>'
                            +'</div>'
                            +'<div class="modal-body"><i class="fa fa-spin fa-spinner"></i> Chargement...</div>'
                            +'<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button></div>';

        if(this.element.parents('.modal').length){
            this.options.embedModal = true;
            this.options.modal = this.element.parents('.modal');
            this.options.modalContent = $('<div class="modal-content">'+tmplModalContent+'</div>');
        }else{
            this.options.modal = $('<div class="modal fade modal-cms-link-selecter" tabindex="-1" role="dialog">'
                +'<div class="modal-dialog modal-lg">'
                    +'<div class="modal-content">'+tmplModalContent+'</div>'
                +'</div></div>');
            this.element.append(this.options.modal);
            this.options.modalContent = this.options.modal.find('.modal-content');
        }

        // charge l'interface Link Picker uniquement une fois
        this.element.one('do-load-picker', $.proxy(function(){
            this.options.modalContent.find('.modal-body').load(this.options.picker_api);
        }, this));

        // lorsque l'utilisateur clique sur "Modifier"
        this._on( this.options.editLink, {
            click: "displayPicker"
        });

        if(!this.options.required){
            this._on( this.options.removeLink, {
                click: "removeLink"
            });
        }

        this._on( this.element.find('input[name*="[display]"]'), {
            change: 'updateDisplay'
        });
        this.element.find('input[name*="[display]"]').trigger('change');
    },

    updateDisplay: function(){
        this.options.label.html( this.element.find('input[name*="[display]"]').val().length ? this.element.find('input[name*="[display]"]').val() : '...' );
        if(this.options.removeLink){
            if(this.element.find('input[name*="[display]"]').val().length){
                this.options.removeLink.show();
            }else{
                this.options.removeLink.hide();
            }
        }
    },

    /**
     * Affiche la page de sélection de lien
     * @param {type} e
     * @returns {undefined}
     */
    displayPicker: function(e){
        e.preventDefault();

        this.listenSelect();

        if(this.options.embedModal){

            // On masque la modal d'origine et on remplace par la notre
            this.options.modal.find('.modal-content')
                    .addClass('original-content')
                    .hide()
                    .after(this.options.modalContent);

            this.options.modalEventHandler = $.proxy(this.preventCloseModal, this);
            this.options.modal.on('hide.bs.modal', this.options.modalEventHandler);
        }else{
            this.options.modal.modal('show');
            this.options.modal.on('hide.bs.modal', $.proxy(this.stopListenSelect, this));
        }

        this.element.trigger('do-load-picker');
    },

    // Dans le cas où on est dans une modal imbriqué, la fermeture est remplacé par la bascule vers l'ancienne modal
    preventCloseModal: function(e){
        e.preventDefault();

        this.options.modalContent.detach();
        this.options.modal.find('.original-content').removeClass('original-content').show();
        this.options.modal.off('hide.bs.modal', this.options.modalEventHandler);

        this.stopListenSelect();
    },

    listenSelect: function(){
        this._on( $(window), {
            select_link: "selectLink"
        });
    },

    stopListenSelect: function(){
        this._off( $(window), 'select_link');
    },

    selectLink: function(e, item){
        display = '<strong class="text-muted">'+item.labelType+' :</strong> <strong>'+item.title+'</strong>';
        if(item.context){
            display += ' (context: '+item.context+')';
        }

        this.options.label.html(display);

        this.element.find('input[name*="[type]"]').val(item.type);
        this.element.find('input[name*="[identifier]"]').val(item.identifier);
        this.element.find('input[name*="[context]"]').val(item.context);
        this.element.find('input[name*="[url]"]').val(item.url);
        this.element.find('input[name*="[display]"]').val(display).trigger('change');

        if(this.options.embedModal){
            this.options.modal.find('.original-content').removeClass('original-content').show();
            this.options.modalContent.detach();

            this.options.modal.off('hide.bs.modal', this.options.modalEventHandler);
            this.stopListenSelect();
        }else{
            this.options.modal.modal('hide');
        }
    },

    removeLink: function(e){
        e.preventDefault();

        this.element.find('input').val('').trigger('change');
    }

});
