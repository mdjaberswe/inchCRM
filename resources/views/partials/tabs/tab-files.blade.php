<div class='full'>
    <h4 class='title-type-a'>Files</h4>

    <div class='right-top'>
        <div class='dropdown dark inline-block'>
            <a class='btn md btn-type-a first dropdown-toggle' animation='fadeIn|fadeOut' data-toggle='dropdown' aria-expanded='false' style='margin-bottom: 5px'>
            	<i class='mdi mdi-file-plus'></i></span> Add File
            </a>

            <ul class='dropdown-menu up-caret'>		    			
                <li><a class='add-multiple' data-item='file' data-action='{!! route('admin.file.store') !!}' data-content='partials.modals.upload-file' data-default='{!! 'linked_type:' . $module_name . '|linked_id:' . $module_id !!}' save-new='false' data-modalsize='medium' modal-title='Add Files'><i class='fa fa-upload'></i> From Computer</a></li>
                <li><a><i class='mdi mdi-google-drive'></i> From Google Drive</a></li>
                <li><a><i class='mdi mdi-dropbox'></i> From Dropbox</a></li>
                <li><a><i class='mdi mdi-onedrive'></i> From OneDrive</a></li>
                <li><a class='add-multiple' data-item='link' data-action='{!! route('admin.link.store') !!}' data-content='partials.modals.add-link' data-default='{!! 'linked_type:' . $module_name . '|linked_id:' . $module_id !!}' save-new='false' data-modalsize='' modal-title='Add Link'><i class='fa fa-link'></i> Add Link</a></li>
            </ul>
        </div>
    </div>

    <table id='datatable' class='table display responsive' cellspacing='0' width='100%' dataurl='{!! 'file-data/' . $module_name . '/' . $module_id !!}' datacolumn='{!! $files_table['json_columns'] !!}' databtn='{!! table_showhide_columns($files_table) !!}' perpage='10'>
        <thead>
            <tr>
                <th data-priority='1' data-class-name='all'>name</th>
                <th data-priority='3'>uploaded&nbsp;by</th>	
                <th data-priority='5' style='min-width: 90px; max-width: 100px'>date&nbsp;modified</th>	
                <th data-priority='4'>size</th>    
                <th data-priority='2' data-orderable='false' data-class-name='align-r all' class='action-2'></th>       			      			        			
            </tr>
        </thead>
    </table>
</div> <!-- end full -->