<div class='modal-body perfectscroll'>
    <div class='form-group m-bottom-force-0'>
        <div class='col-xs-12'>
            <div class='table-filter none'>
                {!! table_filter_html($attendees_table['filter_input'], 'event_attendee', true) !!}
            </div>
            <table id='modal-datatable' class='table middle' cellspacing='0' width='100%' data-item='event_attendee' data-url='{!! 'event-attendee-data' !!}' data-column='{!! $attendees_table['json_columns'] !!}'>
                <thead>
                    <tr>
                        <th>NAME</th>
                        <th>PHONE</th>
                        <th>EMAIL</th>
                        <th>TYPE</th>
                    </tr>
                </thead>
            </table>
        </div>  
    </div> <!-- end form-group -->
</div> <!-- end modal-body -->  