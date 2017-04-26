/**
 * Widget pour sélectionner des pages du CMS
 */
$.widget("kalamu.cmsLinkSelector", {
    options: {
        picker_api: '',
        editLink: null,
        label: null,
        modal: null,
        required : null
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

        this.options.modal = $('<div class="modal fade modal-cms-link-selecter" tabindex="-1" role="dialog">'
            +'<div class="modal-dialog modal-lg">'
                +'<div class="modal-content"><div class="modal-header">'
                    +'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
                    +'<h4 class="modal-title">Sélectionner un contenu</h4>'
                +'</div>'
                +'<div class="modal-body"><i class="fa fa-spin fa-spinner"></i> Chargement...</div>'
                +'<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button></div></div>'
            +'</div></div>');

        this.element.append('<div class="clearfix"></div>');
        this.element.append(this.options.label);
        this.element.append('<div class="clearfix"></div>');
        this.element.append(this.options.editLink);
        if(!this.options.required){
            this.element.append(this.options.removeLink);
        }

        this.element.append(this.options.modal);

        this.options.modal.one('show.bs.modal', $.proxy(function(){
            this.options.modal.find('.modal-body').load(this.options.picker_api);
        }, this));

        this._on( this.options.editLink, {
            click: "openModal"
        });

        if(!this.options.required){
            this._on( this.options.removeLink, {
                click: "removeLink"
            });
        }

        // On écoute les sélection de lien seulement quand c'est notre modal qui est ouverte
        this.options.modal.on('show.bs.modal', $.proxy(this.listenSelect, this));
        this.options.modal.on('hide.bs.modal', $.proxy(this.stopListenSelect, this));

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

    openModal: function(e){
        e.preventDefault();
        this.options.modal.modal('show');
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
        this.element.find('input[name*="[display]"]').val(display).trigger('change');

        this.options.modal.modal('hide');
    },

    removeLink: function(e){
        e.preventDefault();

        this.element.find('input').val('').trigger('change');
    }

});
