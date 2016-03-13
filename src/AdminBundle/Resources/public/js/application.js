var App = {

    _isWithTooltips: false,

    init: function () {
        App._tableFilters()
        App._tableSorters()
        App._selectAll()
        App._tooltips()
        App._navDoc()

        $(window).on('resize', App._tooltips)

        $(document).on('shown.bs.tab', function () {
            $(document).trigger('redraw.bs.charts')
        })

        // docs top button
        if ($('.docs-top').length) {
            App._backToTopButton()
            $(window).on('scroll', App._backToTopButton)
        }
    },

    _navDoc: function () {
        // doc nav js
        var $toc    = $('#markdown-toc')
        var $window = $(window)

        if ($toc[0]) {

            maybeActivateDocNavigation()
            $window.on('resize', maybeActivateDocNavigation)

            function maybeActivateDocNavigation () {
                if ($window.width() > 768) {
                    activateDocNavigation()
                } else {
                    deactivateDocNavigation()
                }
            }

            function deactivateDocNavigation() {
                $window.off('resize.theme.nav')
                $window.off('scroll.theme.nav')
                $toc.css({
                    position: '',
                    left: '',
                    top: ''
                })
            }

            function activateDocNavigation() {

                var cache = {}

                function updateCache() {
                    cache.containerTop   = $('.docs-content').offset().top - 40
                    cache.containerRight = $('.docs-content').offset().left + $('.docs-content').width() + 45
                    measure()
                }

                function measure() {
                    var scrollTop = $window.scrollTop()
                    var distance =  Math.max(scrollTop - cache.containerTop, 0)

                    if (!distance) {
                        $($toc.find('li')[1]).addClass('active')
                        return $toc.css({
                            position: '',
                            left: '',
                            top: ''
                        })
                    }

                    $toc.css({
                        position: 'fixed',
                        left: cache.containerRight,
                        top: 40
                    })
                }

                updateCache()

                $(window)
                    .on('resize.theme.nav', updateCache)
                    .on('scroll.theme.nav', measure)

                $('body').scrollspy({
                    target: '#markdown-toc',
                    selector: 'li > a'
                })

                setTimeout(function () {
                    $('body').scrollspy('refresh')
                }, 1000)
            }
        }
    },

    _backToTopButton: function () {
        if ($(window).scrollTop() > $(window).height()) {
            $('.docs-top').fadeIn()
        } else {
            $('.docs-top').fadeOut()
        }
    },

    _tooltips: function () {
        if ($(window).width() > 768) {
            if (App._isWithTooltips) return
            App._isWithTooltips = true
            $('[data-toggle="tooltip"]').tooltip()

        } else {
            if (!App._isWithTooltips) return
            App._isWithTooltips = false
            $('[data-toggle="tooltip"]').tooltip('destroy')
        }

    },

    _tableSorters: function () {
        $('[data-sort="table"]').tablesorter({
            headers: {
                0: {sorter: false}
            }
        })
    },

    _tableFilters: function () {
        $('[data-filter="table"]').bind('change keypress', function() {

            var filter = $('[data-filter="table"]').val().trim()

            $.each($('[data-sort="table"] tbody tr td'), function(i, el) {
                $(el).parent().show();
                $(el).css('font-weight', 'normal');
            })

            if (filter.length > 0) {
                $.each($('[data-sort="table"] tbody tr td:nth-child(' + $(this).attr('data-column') + ')'), function(i, el) {
                    if (-1 == $(el).text().toLowerCase().indexOf(filter)) {
                        $(el).parent().hide();
                    } else {
                        $(el).css('font-weight', 'bold');
                    }
                })
            } else {
                $.each($('[data-sort="table"] tbody tr td'), function(i, el) {
                    $(el).parent().show();
                    $(el).css('font-weight', 'normal');
                })
            }
        })
    },

    _selectAll: function () {
        $('[class="select-all"]').bind('click', function(){
            $('input.select-row').prop('checked', $(this).is(':checked'));
        })
    }
}

App.init()
