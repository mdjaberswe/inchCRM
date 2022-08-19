        <div id='content-loader'></div>

        <script>
        	var globalVar = {};
            globalVar.ajaxRequest = [];
            globalVar.defaultDropdown = [];
        	globalVar.baseUrl = '{!! url('/') !!}';
        	globalVar.baseAdminUrl = '{!! url('/admin') !!}';
            globalVar.baseCurrencyId = '{!! $base_currency->id !!}';
            globalVar.dataTable = [];
            globalVar.pieChart = [];
            globalVar.timelineChart = [];
            globalVar.orgChart = [];
            globalVar.dropzone = [];
            globalVar.successNotify = {
                                        showProgressbar: true,
                                        placement: { from: 'bottom', align: 'right' },
                                        offset: {x: 20, y: 25},
                                        delay: 3000,
                                        timer: 260,
                                        animate: { enter: 'animated fadeInRight', exit: 'animated fadeOutUp' },
                                        template : "<div data-notify='container' class='alert alert-success slight' role='alert'>" +
                                                        "<span class='fa fa-check-circle'></span>" +
                                                        "<button type='button' class='close' data-notify='dismiss'><span aria-hidden='true'>&times;</span></button>" +
                                                        "{2}" +
                                                        "<div class='progress' data-notify='progressbar'>" +
                                                            "<div class='progress-bar' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width: 10%;'></div>" +
                                                        "</div>" +
                                                    "</div>"  
                                      };

            globalVar.dangerNotify = {
                                        showProgressbar: true,
                                        placement: { from: 'bottom', align: 'right' },
                                        offset: {x: 20, y: 25},
                                        delay: 3000,
                                        timer: 260,
                                        animate: { enter: 'animated fadeInRight', exit: 'animated fadeOutUp' },
                                        template : "<div data-notify='container' class='alert alert-danger slight' role='alert'>" +
                                                        "<span class='fa fa-exclamation-circle'></span>" +
                                                        "<button type='button' class='close' data-notify='dismiss'><span aria-hidden='true'>&times;</span></button>" +
                                                        "{2}" +
                                                        "<div class='progress' data-notify='progressbar'>" +
                                                            "<div class='progress-bar' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width: 10%;'></div>" +
                                                        "</div>" +
                                                    "</div>"  
                                      };                            
        </script>

        @include('partials.global-scripts')
        
        @stack('scripts')

        {!! HTML::script('js/fallback.js') !!}

    </body>
</html>