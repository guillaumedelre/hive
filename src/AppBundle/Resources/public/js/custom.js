(function($) {

    "use strict";

    var options = {
        //events_source: function () { return []; },
        events_source: '/api/events',
        view: 'month',
        tmpl_path: '/bower_components/bootstrap-calendar/tmpls/',
        tmpl_cache: false,
        day: new Date().toJSON().slice(0,10),
        onAfterEventsLoad: function(events) {
            if(!events) {
                return;
            }
            var list = $('#eventlist');
            list.html('');

            $.each(events, function(key, val) {
                //var eventDate = moment(new Date(val.start).toJSON().slice(0, 10)).format('DD/MM/YYYY');
                var eventDate = moment(new Date(val.start).toJSON().slice(0, 10)).fromNow();
                $(document.createElement('li'))
                    .html('<a href="' + val.url + '">' + val.title + '<span class="label label-default pull-right">' + eventDate + '</span></a>')
                    .appendTo(list);
            });
        },
        onAfterViewLoad: function(view) {
            $('.page-header h3').text(this.getTitle());
            $('.btn-group button').removeClass('active');
            $('button[data-calendar-view="' + view + '"]').addClass('active');
        },
        classes: {
            months: {
                general: 'label'
            }
        }
    };

    var calendar = $('#calendar').calendar(options);

    $('.btn-group button[data-calendar-nav]').each(function() {
        var $this = $(this);
        $this.click(function() {
            calendar.navigate($this.data('calendar-nav'));
        });
    });

    $('.btn-group button[data-calendar-view]').each(function() {
        var $this = $(this);
        $this.click(function() {
            calendar.view($this.data('calendar-view'));
        });
    });

    $('#first_day').change(function(){
        var value = $(this).val();
        value = value.length ? parseInt(value) : null;
        calendar.setOptions({first_day: value});
        calendar.view();
    });

    $('#language').change(function(){
        calendar.setLanguage($(this).val());
        calendar.view();
    });

    $('#events-in-modal').change(function(){
        var val = $(this).is(':checked') ? $(this).val() : null;
        calendar.setOptions({modal: val});
    });
    $('#format-12-hours').change(function(){
        var val = $(this).is(':checked') ? true : false;
        calendar.setOptions({format12: val});
        calendar.view();
    });
    $('#show_wbn').change(function(){
        var val = $(this).is(':checked') ? true : false;
        calendar.setOptions({display_week_numbers: val});
        calendar.view();
    });
    $('#show_wb').change(function(){
        var val = $(this).is(':checked') ? true : false;
        calendar.setOptions({weekbox: val});
        calendar.view();
    });
//    $('#events-modal .modal-header, #events-modal .modal-footer').click(function(e){
        //e.preventDefault();
        //e.stopPropagation();
//    });

    $('.delete-link').bind('click', function(e){
        e.preventDefault();
        if (confirm("Are you sure you want to delete ?")) {
            window.location.href = $(this).attr('href');
        }
    });

    $('#first_day').trigger('change');
    $('#language').trigger('change');

}(jQuery));