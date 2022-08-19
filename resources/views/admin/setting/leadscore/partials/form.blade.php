<div class='modal-body perfectscroll'>
    <div class='form-group show-if multiple related_to-input' data-slide='true'>
        <label for='related_to' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Rule Related To <span class='c-danger'>*</span></label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            {!! Form::select('related_to', $related_to_list, null, ['class' => 'form-control white-select-type-single-b']) !!}
            <span field='related_to' class='validation-error'></span>
        </div>
    </div> <!-- end form-group -->

    <div class='full related_to-input parent-show none'>
        <div class='form-group full none lead_property-list'>
            <div class='form-group show-if multiple'>
                <label for='lead_property' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Property Name <span class='c-danger'>*</span></label>
                
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    {!! Form::select('lead_property', $lead_property_list, null, ['class' => 'form-control white-select-type-single', 'data-for-child' => 'true']) !!}
                    <span field='lead_property' class='validation-error'></span>
                </div>
            </div>   

            <div class='full parent-show none'>
                <div class='form-group none {!! $lead_property_css['string_css'] !!}'>
                    <label for='string_condition' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Property Condition <span class='c-danger'>*</span></label>
                    
                    <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                        <select name='string_condition' class='form-control multiple-child white-select-type-single-b' data-child='condition-val'>
                            {!! option_attr_render($condition_list['string']) !!}
                        </select>
                        <span field='string_condition' class='validation-error'></span>
                    </div>
                </div> <!-- end form-group -->

                <div class='form-group none {!! $lead_property_css['dropdown_css'] !!}' data-for-parent='true'>
                    <label for='dropdown_condition' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Property Condition <span class='c-danger'>*</span></label>
                    
                    <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                        <select name='dropdown_condition' class='form-control multiple-child white-select-type-single-b' data-child='condition-val'>
                            {!! option_attr_render($condition_list['dropdown']) !!}
                        </select>
                        <span field='dropdown_condition' class='validation-error'></span>
                    </div>
                </div> <!-- end form-group -->

                <div class='form-group none {!! $lead_property_css['numeric_css'] !!}'>
                    <label for='numeric_condition' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Property Condition <span class='c-danger'>*</span></label>
                    
                    <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                        <select name='numeric_condition' class='form-control multiple-child white-select-type-single-b' data-child='condition-val'>
                            {!! option_attr_render($condition_list['numeric']) !!}
                        </select>
                        <span field='numeric_condition' class='validation-error'></span>
                    </div>
                </div> <!-- end form-group -->

                <div class='form-group none  {!! $lead_property_css['date_css'] !!}'>
                    <label for='date_condition' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Property Condition <span class='c-danger'>*</span></label>
                    
                    <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                        <select name='date_condition' class='form-control multiple-child white-select-type-single-b' data-child='condition-val'>
                            {!! option_attr_render($condition_list['date']) !!}
                        </select>
                        <span field='date_condition' class='validation-error'></span>
                    </div>
                </div> <!-- end form-group -->
            </div> <!-- end condition container -->    

            <div class='form-group condition-val none' data-for='string'>
                <label for='string_value' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Conditional Value <span class='c-danger'>*</span></label>
                
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    {!! Form::select('string_value[]', [], null, ['data-placeholder' => 'Enter a value', 'multiple' => 'multiple', 'class' => 'form-control white-select-type-multiple-tags']) !!}
                    <span field='string_value' class='validation-error'></span>
                </div>
            </div> <!-- end form-group --> 

            <div class='form-group condition-val none' data-for='numeric'>
                <label for='numeric_value' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Conditional Value <span class='c-danger'>*</span></label>
                
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    {!! Form::text('numeric_value', null, ['placeholder' => 'Enter a value', 'class' => 'form-control numeric']) !!}
                    <span field='numeric_value' class='validation-error'></span>
                </div>
            </div> <!-- end form-group -->

            <div class='form-group condition-val none' data-for='days'>
                <label for='days_value' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Conditional Value <span class='c-danger'>*</span></label>
                
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    {!! Form::select('days_value', $dropdown['days'], null, ['class' => 'form-control white-select-type-single-b', 'data-placeholder' => 'Please select a value']) !!}
                    <span field='days_value' class='validation-error'></span>
                </div>
            </div> <!-- end form-group -->

            <div class='form-group condition-val none' data-for='currency_id'>
                <label for='currency_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Conditional Value <span class='c-danger'>*</span></label>
                
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    {!! Form::select('currency_id[]', $dropdown['currency'], null, ['class' => 'form-control white-select-type-multiple', 'multiple' => 'multiple', 'data-placeholder' => 'Please select values']) !!}
                    <span field='currency_id' class='validation-error'></span>
                </div>
            </div> <!-- end form-group -->

            <div class='form-group condition-val none' data-for='no_of_employees'>
                <label for='no_of_employees' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Conditional Value <span class='c-danger'>*</span></label>
                
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    {!! Form::select('no_of_employees[]', $dropdown['no_of_employees'], null, ['class' => 'form-control white-select-type-multiple', 'multiple' => 'multiple', 'data-placeholder' => 'Please select values']) !!}
                    <span field='no_of_employees' class='validation-error'></span>
                </div>
            </div> <!-- end form-group -->

            <div class='form-group condition-val none' data-for='created_by'>
                <label for='created_by' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Conditional Value <span class='c-danger'>*</span></label>
                
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    {!! Form::select('created_by[]', $dropdown['responsible_person'], null, ['class' => 'form-control white-select-type-multiple', 'multiple' => 'multiple', 'data-placeholder' => 'Please select values']) !!}
                    <span field='created_by' class='validation-error'></span>
                </div>
            </div> <!-- end form-group -->

            <div class='form-group condition-val none' data-for='modified_by'>
                <label for='modified_by' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Conditional Value <span class='c-danger'>*</span></label>
                
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    {!! Form::select('modified_by[]', $dropdown['responsible_person'], null, ['class' => 'form-control white-select-type-multiple', 'multiple' => 'multiple', 'data-placeholder' => 'Please select values']) !!}
                    <span field='modified_by' class='validation-error'></span>
                </div>
            </div> <!-- end form-group -->

            <div class='form-group condition-val none' data-for='lead_owner'>
                <label for='lead_owner' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Conditional Value <span class='c-danger'>*</span></label>
                
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    {!! Form::select('lead_owner[]', $dropdown['responsible_person'], null, ['class' => 'form-control white-select-type-multiple', 'multiple' => 'multiple', 'data-placeholder' => 'Please select values']) !!}
                    <span field='lead_owner' class='validation-error'></span>
                </div>
            </div> <!-- end form-group -->

            <div class='form-group condition-val none' data-for='access'>
                <label for='access' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Conditional Value <span class='c-danger'>*</span></label>
                
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    {!! Form::select('access[]', $dropdown['access'], null, ['class' => 'form-control white-select-type-multiple', 'multiple' => 'multiple', 'data-placeholder' => 'Please select values']) !!}
                    <span field='access' class='validation-error'></span>
                </div>
            </div> <!-- end form-group -->

            <div class='form-group condition-val none' data-for='country_code'>
                <label for='country_code' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Conditional Value <span class='c-danger'>*</span></label>
                
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    {!! Form::select('country_code[]', $dropdown['country'], null, ['class' => 'form-control white-select-type-multiple', 'multiple' => 'multiple', 'data-placeholder' => 'Please select values']) !!}
                    <span field='country_code' class='validation-error'></span>
                </div>
            </div> <!-- end form-group -->

            <div class='form-group condition-val none' data-for='campaign_id'>
                <label for='campaign_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Conditional Value <span class='c-danger'>*</span></label>
                
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    {!! Form::select('campaign_id[]', $dropdown['campaign'], null, ['class' => 'form-control white-select-type-multiple', 'multiple' => 'multiple', 'data-placeholder' => 'Please select values']) !!}
                    <span field='campaign_id' class='validation-error'></span>
                </div>
            </div> <!-- end form-group -->

            <div class='form-group condition-val none' data-for='event_id'>
                <label for='event_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Conditional Value <span class='c-danger'>*</span></label>
                
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    {!! Form::select('event_id[]', $dropdown['event'], null, ['class' => 'form-control white-select-type-multiple', 'multiple' => 'multiple', 'data-placeholder' => 'Please select values']) !!}
                    <span field='event_id' class='validation-error'></span>
                </div>
            </div> <!-- end form-group -->

            <div class='form-group condition-val none' data-for='source_id'>
                <label for='source_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Conditional Value <span class='c-danger'>*</span></label>
                
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    {!! Form::select('source_id[]', $dropdown['source'], null, ['class' => 'form-control white-select-type-multiple', 'multiple' => 'multiple', 'data-placeholder' => 'Please select values']) !!}
                    <span field='source_id' class='validation-error'></span>
                </div>
            </div> <!-- end form-group -->

            <div class='form-group condition-val none' data-for='lead_stage_id'>
                <label for='lead_stage_id' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Conditional Value <span class='c-danger'>*</span></label>
                
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    {!! Form::select('lead_stage_id[]', $dropdown['stage'], null, ['class' => 'form-control white-select-type-multiple', 'multiple' => 'multiple', 'data-placeholder' => 'Please select values']) !!}
                    <span field='lead_stage_id' class='validation-error'></span>
                </div>
            </div> <!-- end form-group -->

            <div class='form-group condition-val none' data-for='last_activity_type'>
                <label for='last_activity_type' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Conditional Value <span class='c-danger'>*</span></label>
                
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    {!! Form::select('last_activity_type[]', $dropdown['activity_type'], null, ['class' => 'form-control white-select-type-multiple', 'multiple' => 'multiple', 'data-placeholder' => 'Please select values']) !!}
                    <span field='last_activity_type' class='validation-error'></span>
                </div>
            </div> <!-- end form-group -->
        </div> <!-- end lead property -->   

        <div class='form-group full none email_activity-list'>
            <div class='form-group'>
                <label for='subject' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Email Subject <span class='c-danger'>*</span></label>
                
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    {!! Form::text('subject', null, ['placeholder' => 'Enter subject', 'class' => 'form-control']) !!}
                    <span field='subject' class='validation-error'></span>
                </div>
            </div> <!-- end form-group -->

            <div class='form-group'>
                <label for='email_activity' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Activity <span class='c-danger'>*</span></label>
            
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    {!! Form::select('email_activity', ['' => '-None-', 'opened' => 'Opened', 'clicked' => 'Clicked'], null, ['class' => 'form-control white-select-type-single-b']) !!}
                    <span field='email_activity' class='validation-error'></span>
                </div>
            </div> <!-- end form-group -->

            <div class='form-group'>
                <label for='email_condition' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Activity Condition <span class='c-danger'>*</span></label>
                
                <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
                    {!! Form::select('email_condition', $condition_list['email'], null, ['class' => 'form-control white-select-type-single-b']) !!}
                    <span field='email_condition' class='validation-error'></span>
                </div>
            </div> <!-- end form-group -->
        </div> <!-- end email activity -->
    </div> <!-- end related to input -->

    <div class='form-group scoring_type-input'>
        <label for='scoring_type' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Scoring Type</label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            <select name='scoring_type' class='form-control icon-changer white-select-type-single-b' data-child='score-sign'>
                <option value='1' data-icon='fa fa-plus'>Positive</option>
                <option value='0' data-icon='fa fa-minus'>Negative</option>
            </select>
            <span field='scoring_type' class='validation-error'></span>
        </div>
    </div> <!-- end form-group -->

    <div class='form-group score-input'>
        <label for='score' class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>Score</label>
        
        <div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>
            <div class='full left-icon'>
                <i class='fa fa-plus' data-parent='score-sign'></i>
                {!! Form::text('score', null, ['placeholder' => 'Enter score (ex. 10)', 'class' => 'form-control numeric']) !!}
                <span field='score' class='validation-error'></span>
            </div>    
        </div>
    </div> <!-- end form-group -->

    {!! Form::hidden('lead_score_id', 0) !!}
    {!! Form::hidden('score_only', 0) !!}
</div> <!-- end modal-body -->

@if(isset($form) && $form == 'edit')
    {!! Form::hidden('id', null) !!}
@endif