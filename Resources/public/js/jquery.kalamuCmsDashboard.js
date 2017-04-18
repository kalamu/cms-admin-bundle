
$.kalamu.kalamuDashboard.prototype.save = function(e){
    if(e){
        e.preventDefault();
    }

    datas = this.export();
    delete datas.label;
    $(this.options.textarea).text( JSON.stringify(datas) );
};


$.kalamu.kalamuDashboard.prototype.refresh = function(){
    if($(this.options.textarea).text()){
        datas = JSON.parse( $(this.options.textarea).text() );
    }else{
        datas = {};
    }

    this.options.config = datas;
    this.element.find('.kalamu-dashboard-row').remove();

    if(this.options.config.rows){
        $.each(this.options.config.rows, $.proxy(function(i, row_config){
            row = $('<div>');
            this.element.append(row);
            row.kalamuDashboardRow(row_config);
        }, this));
    }

};