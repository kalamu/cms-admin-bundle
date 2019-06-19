
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
     * Widget to show available content for menu edition
     */
    $.widget("custom.MenuPickerApi", {
        options: {
            label: '', // label of the picker
            name: '', // name of the picker
            type: '', // Content type
            url: '',
            context: '',
            page: 1,
            nb_pages: 1,
            search: '',
            displayItem: null
        },

        _create: function() {
            this.element.find('.content-picker').addClass('panel-scroller scroller-sm scroller-thick scroller-pn pn');

            this.element.find('.content-picker tbody tr').remove();

            this._on( this.element.find('.page-previous'), {
                click: 'pagePrevious'
            });
            this._on( this.element.find('.page-next'), {
                click: 'pageNext'
            });
            this._on( this.element.find('.page-input'), {
                change: 'changePage',
                keydown: 'preventSubmit'
            });
            this._on( this.element.find('.search-input'), {
                change: 'search',
                keydown: 'preventSubmit'
            });
            this.element.find('.search-input').val('');

            if(this.element.find('.context-filter').length){
                this._on( this.element.find('.context-filter a[data-context]'), {
                    click: 'filterContext'
                });
            }

            this._refresh();
        },

        _refresh: function() {
            this.element.find('.panel-controls').prepend('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: this.options.url,
                context: this,
                type: 'GET',
                dataType: 'json',
                data: {
                    page: this.options.page,
                    context: this.options.context,
                    search: this.options.search
                },
                success: function(obj){
                    this.options.nb_pages = obj.nb_pages;

                    this.element.find('.total-count').text(obj.nb_contenu);
                    this.element.find('.page-count').text(obj.nb_pages);
                    this.element.find('.page-input').val(this.options.page);

                    if(this.options.page == 1){
                        this.element.find('.page-previous').addClass('disabled');
                    }else{
                        this.element.find('.page-previous').removeClass('disabled');
                    }

                    if(this.options.page == obj.nb_pages){
                        this.element.find('.page-next').addClass('disabled');
                    }else{
                        this.element.find('.page-next').removeClass('disabled');
                    }

                    if(obj.nb_pages == 1){
                        this.element.find('.page-input').attr('disabled', 'disabled');
                    }else{
                        this.element.find('.page-input').removeAttr('disabled');
                    }

                    this.element.find('.content-picker tbody tr').remove();

                    disaply_method = typeof this.options.displayItem === 'function' ? this.options.displayItem : this._displayItem;
                    $(obj.contenus).each($.proxy(disaply_method, this));

                    this.element.find('.panel-controls .fa-spinner').remove();
                },
                error: function(){
                    this.element.find('.panel-controls .fa-spinner').remove();
                    alert("An error occured. Try to reload the page if the problem persist.");
                }
            });
        },

        _displayItem: function(i, item){
            addBtn = $('<a href="#" class"btn btn-xs btn-info"><i class="fa fa-plus"></i></a>');
            item.name = this.options.name;
            item.labelType = this.options.label;
            addBtn.data('menu-item', item);
            addBtn.click(function(e){
                e.preventDefault();
                $(window).trigger('add-menu-item', $(this).data('menu-item'));
            });

            row = $('<tr><td>'+item.title+'</td><td></td></tr>');
            row.find('td:last').append(addBtn);
            row.appendTo( this.element.find('.content-picker tbody') );
        },

        pageNext: function(e){
            e.preventDefault();

            if(this.options.page < this.options.nb_pages){
                this.options.page = this.options.page+1;
                this._refresh();
            }
        },

        pagePrevious: function(e){
            e.preventDefault();

            if(this.options.page > 1){
                this.options.page = this.options.page-1;
                this._refresh();
            }
        },

        changePage: function(e){
            e.stopPropagation();
            e.preventDefault();

            page = parseInt(this.element.find('.page-input').val());
            if(isNaN(page)){
                page = 1;
            }
            if(page > this.options.nb_pages){
                this.options.page = this.options.nb_pages;
            }else if(page < 1){
                this.options.page = 1;
            }else{
                this.options.page = page;
            }
            this._refresh();
        },

        search: function(e){
            e.stopPropagation();
            e.preventDefault();

            this.options.page = 1;
            this.options.search = this.element.find('.search-input').val().trim();
            this._refresh();
        },

        filterContext: function(e){
            this.options.context = $(e.target).attr('data-context');
            this.element.find('.context-filter li.active').removeClass('active');
            $(e.target).parent().addClass('active');

            if($(e.target).parents('.dropdown-menu').length){
                this.element.find('.context-filter .dropdown-menu').append( this.element.find('.context-filter>ul>li:eq(1)').detach() );
                this.element.find('.context-filter>ul>li:eq(0)').after($(e.target).parent().detach());
            }

            this.options.page = 1;

            this._refresh();
        },

        // Prevent to send the form if press "Enter"
        preventSubmit: function(e){
            if(e.which == 13){
                e.preventDefault();
                if($(e.target).hasClass('page-input')){
                    this.changePage(e);
                }else if($(e.target).hasClass('search-input')){
                    this.search(e);
                }
            }
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