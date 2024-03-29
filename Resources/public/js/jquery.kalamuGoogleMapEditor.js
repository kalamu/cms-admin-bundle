
/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$.widget( "kalamu.googleMapMarkerWidgetEditor", {

    options: {
        marker: null,
        map: null,
        content: null
    },

    _create: function() {
        if('undefined' === typeof google.maps){
            console.error("The Google Maps library is not loaded");
        }

        this.options.marker = new google.maps.Marker({
            position: new google.maps.LatLng({
                lat: parseFloat(this.element.find('input[name*="latitude"]').val()),
                lng: parseFloat(this.element.find('input[name*="longitude"]').val())}),
            draggable: true,
            map: this.options.map
        });

        this.options.content = $('<div style="width: 360px; margin-right: 25px;">'
                    +'<div class="form-group"><input type="text" name="title" placeholder="Marker title" class="form-control" /></div>'
                    +'<div class="form-group"><textarea name="description" class="form-control" placeholder="Description"></textarea></div>'
                    +'<div class="checkbox text-left"><label><input type="checkbox" name="default_open" value="1" /> Open the window by default</label></div>'
                    +'<p><a href="#" class="btn btn-danger btn-xs delete-marker"><i class="fa fa-trash"></i> Remove this marker</a></p>'
                    +'</div>');
        this.options.content.find('input[name=title]').val( this.element.find('input[name*=titre]').val() );
        this.options.content.find('textarea').val( this.element.find('textarea[name*="description"]').val() );
        this.options.content.find('input[type=checkbox]').prop('checked', this.element.find('input[name*="default_open"][value=1]').prop('checked') );

        this.options.infoWindow = new google.maps.InfoWindow({
            content: this.options.content[0]
        });

        this.options.marker.addListener('position_changed', $.proxy(this.positionChanged, this));
        this.options.marker.addListener('click', $.proxy(this.click, this));

        google.maps.event.addDomListener( this.options.content.find('input[name=title]')[0], 'change', $.proxy(this.updateTitle, this) );
        google.maps.event.addDomListener( this.options.content.find('input[name=title]')[0], 'keyup', $.proxy(this.updateTitle, this) );
        google.maps.event.addDomListener( this.options.content.find('textarea')[0], 'change', $.proxy(this.updateDescription, this) );
        google.maps.event.addDomListener( this.options.content.find('textarea')[0], 'keyup', $.proxy(this.updateDescription, this) );
        google.maps.event.addDomListener( this.options.content.find('input[type=checkbox]')[0], 'change', $.proxy(this.updateDefaultOpen, this) );
        google.maps.event.addDomListener( this.options.content.find('a.delete-marker')[0], 'click', $.proxy(this.removeMarker, this) );
    },

    positionChanged: function(){
        position = this.options.marker.getPosition();
        this.element.find('input[name*="latitude"]').val(position.lat());
        this.element.find('input[name*="longitude"]').val(position.lng());
    },

    click: function(event){
        this.options.infoWindow.open(this.options.map, this.options.marker);
    },

    updateTitle: function(e){
        this.element.find('input[name*="titre"]').val( $(e.target).val() );
    },

    updateDescription: function(e){
        this.element.find('textarea[name*="description"]').val( $(e.target).val() );
    },

    updateDefaultOpen: function(e){
        this.element.find('input[name*="default_open"][value='+($(e.target).prop('checked') ? '1' : '0')+']').prop('checked', true);
    },

    // update the form
    removeMarker: function(){
        if(!confirm("Are you sure to remove this marker ?")){
            return;
        }

        this.options.marker.setMap(null);
        this.element.remove();
    }
});

$.widget( "kalamu.googleMapWidgetEditor", {

    options: {
        center: null,
        zoom: null,
        map: null,
        markers: null,
        api_key: null
    },

    _create: function() {

        this.options.markers = [];
        if(typeof google === 'undefined'){
            $.getScript('https://maps.google.com/maps/api/js?sensor=false'+(this.options.api_key ? '&key='+this.options.api_key : ''), $.proxy(this.implements, this));
        }else{
            this.implements();
        }

    },

    implements: function(){
        if(typeof google === 'undefined'){ console.log("Google Map is not loaded"); return; }

        this.options.map = new google.maps.Map(this.element.get()[0], {
            center: this.options.center,
            zoom: this.options.zoom
        });

        this.options.map.addListener('center_changed', $.proxy(this.centerChanged, this));
        this.options.map.addListener('zoom_changed', $.proxy(this.zoomChanged, this));

        if(typeof this.options.centerChanged === 'function'){
            this.options.map.addListener('center_changed', $.proxy(this.options.centerChanged, this));
        }
        if(typeof this.options.zoomChanged === 'function'){
            this.options.map.addListener('zoom_changed', $.proxy(this.options.zoomChanged, this));
        }
        if(typeof this.options.mapClicked === 'function'){
            this.options.map.addListener('click', $.proxy(this.options.mapClicked, this));
        }

        this.refresh();
    },

    refresh: function(){

        google.maps.event.trigger(this.options.map, 'center_changed');
        google.maps.event.trigger(this.options.map, 'zoom_changed');
    },

    centerChanged: function(){
        this.options.center.lat = this.options.map.getCenter().lat();
        this.options.center.lng = this.options.map.getCenter().lng();
    },

    zoomChanged: function(){
        this.options.zoom = this.options.map.getZoom();
    }

});