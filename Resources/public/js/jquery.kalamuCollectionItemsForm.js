
/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$.widget( "kalamu.kalamuCollectionItemsForm", {

    options: {
        nb_elements: null,
        item_label: '',
        display_label: true,
        item_filter: null
    },

    _create: function() {
        this.element.addClass('kalamu-collection-items-widget');

        this.options.addLink = $('<a href="#" class="clearfix btn btn-info"><i class="fa fa-arrow-up"></i> Add a '+this.options.item_label+'</a>');
        this.element.after( this.options.addLink );
        this._on( this.options.addLink, {
            click: "addItem"
        });

        this._on( this.element.find('a[data-collection-remove-btn]'), {
            click: "removeItem"
        });

        this.refresh();
    },

    refresh: function(){

        this.element.find('a[data-collection-remove-btn]').each(function(){
            $(this).text("Remove this element").removeClass('btn-default').addClass('btn-warning');
        });

        this.element.find('.collection-item>label').each($.proxy(function(i, item){
            $(item).text(this.options.item_label+' '+i);
        }, this));

        if(!this.options.display_label){
            this.element.find('.collection-item>label').hide();
        }

        if(typeof this.options.item_filter === 'function'){
            this.element.find('.collection-item').each(this.options.item_filter);
        }

    },

    addItem: function(e){
        if(e){
            e.preventDefault();
        }

        newItem = $( this.element.attr('data-prototype').replace(/__name__/g, this.options.nb_elements) );
        this.options.nb_elements++;
        this.element.find('>.col-md-12').append( newItem );

        newItem.find('a[data-collection-remove-btn]').text("Remove this element").toggleClass('btn-default btn-warning').click(function(e){
            e.preventDefault();
            $(e.target).parents('.collection-item').remove();
        });

        this.refresh();
    },

    removeItem: function(e){
        if(e){
            e.preventDefault();
        }

        console.log($(e.target));
        $(e.target).parents('.collection-item').remove();

        this.refresh();
    },

    _delete: function(e){
        e.preventDefault();
        this.element.remove();
    }

});