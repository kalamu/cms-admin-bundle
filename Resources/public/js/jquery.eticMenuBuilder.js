
/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

(function($) {

    /**
     * Widget de gestion du menu
     */
    $.widget("etic.menuBuilder", {
        options: {
            field: null // destination du menu serializé
        },

        /**
         * Création du Widget
         * @returns {undefined}
         */
        _create: function() {
            this.element.addClass('menuBuilder');

            // initialisation du menu sortable
            this.element.nestedSortable({
                listType: 'ul',
                placeholder: "ui-state-highlight",
                opacity: 0.6,
                update: $.proxy(this.updateField, this)
            });

            $(window).bind('menu-item-update', $.proxy(this.updateField, this));

            this._refresh();
        },

        _refresh: function() {
            this.element.find('*').remove();
            items = $.parseJSON(this.options.field.val());
            $(items).each($.proxy(function(i, item){
                this.addItem(item);
            }, this));

            this.element.nestedSortable('refresh');

        },

        /**
         * Evenement lancé lorsqu'un item est déplacé dans le menu
         * Met à jour les item en fonction de leur position
         */
        updateField: function(){
            items = [];
            this.element.find('>.menu-item').each($.proxy(function(i, item){
                this.push( $(item).menuItem('serialize') );
            }, items));

            this.options.field.val(JSON.stringify(items));
        },

        /**
         * Ajout d'un nouveau item dans le menu (en fin)
         */
        addItem: function(item){
            $('<li></li>').menuItem(item).appendTo(this.element);
            this.updateField();
        },

        _destroy: function() {
            this.element
                    .removeData("nestedSortable")
                    .unbind(".nestedSortable");
            return $.ui.sortable.prototype.destroy.apply(this, arguments);
        },
        _setOptions: function() {
            this._superApply(arguments);
            this._refresh();
        },
        _setOption: function(key, value) {
            this._super(key, value);
        }
    });



    /**
     * Widget de gestion d'un node du menu
     */
    $.widget("etic.menuItem", {
        options: {
            id: '',
            title: '',
            url: '',
            icon: '',
            class: '',
            type: '',
            content_type: '',
            content_id: '',
            context_enabled: null,
            contexts: null,
            context: null
        },

        _create: function() {
            this.element.addClass('menu-item');

            if(this.options.context_enabled === null && this.options.context){
                this.options.context_enabled = true;
                this.options.contexts = {};
            }

            this.element
                    .prepend('<div class="item-handler ph10"></div>')
                    .append('<ul></ul>');

            this.element.find('.item-handler')
                    .append('<i class="fa fa-fw item-icon"></i> <strong class="item-title"></strong>')
                    .append('<div class="pull-right toggler"><span class="item-context"></span><span class="item-type"></span><i class="fa fa-caret-down"></i></div>');

            $form = $('<div class="item-form"></div>');
            $form.append('<div class="col-md-12"><label class="control-label">Titre* </label>' +
                    '<input type="text" class="form-control" name="title" value="" /></div>');
            $form.append('<div class="col-md-'+(this.options.context_enabled ? 9 : 12)+'"><label class="control-label">Adresse* </label>' +
                    '<input type="text" class="form-control" name="url" value="" />' +
                    '<a href="#" class="item-url" target="_blank"><i class="fa fa-external-link"></i> Ouvrir</a></div>');
            if(this.options.context_enabled){
                $form.append('<div class="col-md-3"><label class="control-label">Context* </label>' +
                    '<select class="form-control" name="context"></select></div>');
            }

            $form.append('<div class="col-md-6 icon-selector"><label class="control-label text-muted">Icône </label><br />' +
                    '<span class="btn btn-default"><i class="fa fa-fw item-icon"></i></span><input type="text" class="form-control pull-left" name="icon" value="" style="width: auto" /></div>'+
                    '<div class="col-md-6"><label class="control-label text-muted">Class (CSS) </label>'+
                    '<input type="text" class="form-control" name="class" value="" /></div>');
            $form.append('<span class="clearfix"></span>');
            $form.append('<div class="mt20 text-center form-buttons"></div>');

            editButton = $('<a class="btn btn-sm btn-success m5" href="#"><i class="fa fa-save"></i> Enregistrer</a> ');
            removeButton = $('<a class="btn btn-sm btn-danger m5" href="#"><i class="fa fa-close"></i> Supprimer</a> ');
            $form.find('.form-buttons').append(editButton).append(removeButton).append('<span class="clearfix"></span>');
            $form.hide();
            this.element.find('.item-handler').append($form);

            $form.find('input[name=icon]').iconpicker({
                placement: 'topLeft',
                hideOnSelect: true
            }).on('iconpickerSelected change', $.proxy(function(e){
                $(e.target).blur();
                $(e.target).parent().find('.item-icon').attr('class', 'item-icon fa fa-fw '+$(e.target).val());
            }, this));


            this._on( this.element.find('.toggler'), { click: "toggle" });
            this._on( editButton, { click: "edit" });
            this._on( removeButton, { click: "remove" });
            this._on( $form.find('input'), { keydown: 'preventSubmit' });

            if(this.options.children){
                $.each(this.options.children, $.proxy(function(i, child){
                    $('<li></li>').menuItem(child).appendTo( this.element.find('>ul') );
                }, this));
            }

            this._refreshContentInfos();
            this._refresh();
        },

        _refresh: function() {

            this.element.find('>.item-handler .item-title').text(this.options.title);
            this.element.find('>.item-handler .item-type').text(this.options.type);
            this.element.find('>.item-handler input[name=title]').val(this.options.title);
            this.element.find('>.item-handler input[name=url]').val(this.options.url);
            this.element.find('>.item-handler input[name=icon]').val(this.options.icon);
            this.element.find('>.item-handler input[name=class]').val(this.options.class);
            this.element.find('>.item-handler .item-url').attr('href', this.options.url);
            this.element.find('>.item-handler .item-icon').attr('class', 'item-icon').addClass('fa fa-fw '+this.options.icon);

            if(this.options.context_enabled){
                this.element.find('select[name="context"] option').remove();
                $.each(this.options.contexts, $.proxy(function(name, label){
                    this.find('select[name="context"]').append('<option value="'+name+'">'+label+'</option>');
                }, this.element));
                this.element.find('select[name="context"] option[value="'+this.options.context+'"]').prop('selected', true);

                context = this.element.find('select[name="context"] option[value="'+this.options.context+'"]').text();
                this.element.find('>.item-handler .item-context').text(context+' :: ');
            }
        },

        // Met à jours les infos du content
        _refreshContentInfos: function(){
            // récupération de la réponse
            $(window).one('menu-item-'+this.options.content_type+'-'+this.options.content_id, $.proxy(function(e, data){
                if(typeof data.contexts === 'object'){
                    this.options.context_enabled = true;
                    this.options.contexts = data.contexts;
                }
                this.options.url = data.url;
                this._refresh();
            }, this));

            // envoi d'une demande de réactualisation
            $(window).trigger('refresh-menu-item-'+this.options.content_type, this.options);

        },

        toggle: function(e){
            this.element.find('>.item-handler .item-form').slideToggle('fast');
            this.element.find('>.item-handler .toggler .fa').toggleClass("fa-caret-down fa-caret-up");
        },

        edit: function(e){
            e.preventDefault();

            this.options.title = this.element.find('>.item-handler input[name=title]').val();
            this.options.url = this.element.find('>.item-handler input[name=url]').val();
            this.options.icon = this.element.find('>.item-handler input[name=icon]').val();
            this.options.class = this.element.find('>.item-handler input[name=class]').val();
            if(this.options.context_enabled){
                this.options.context = this.element.find('select[name="context"]').val();
            }
            this._refresh();
            this._refreshContentInfos();
            $(window).trigger('menu-item-update');
            this.toggle();
        },

        // éviter d'envoyer le formulaire lorsqu'on fait "Entrer"
        preventSubmit: function(e){
            if(e.which == 13){
                e.preventDefault();
                this.edit(e);
            }
        },

        remove: function(e){
            e.preventDefault();
            this._destroy();
            $(window).trigger('menu-item-update');
        },

        serialize: function(){
            var obj = this.options;
            obj.children = this.element.find('>ul>.menu-item').map(function(){
                return $(this).menuItem('serialize');
            }).get();

            delete obj.disabled;
            delete obj.create;

            return obj;
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



})(jQuery);