<?php

return array(

    'lead_funnel'   => [

        /*
        |--------------------------------------------------------------------------
        | Lead Created Time - any|yesterday|today|tommorrow|last_month|current_month|next_month|
        |                     last_7_days|last_30_days|last_60_days|last_90_days|last_120_days|
        |                     last_6_months|last_12_months|next_7_days|next_30_days|next_60_days|
        |                     next_90_days|next_120_days|next_6_months|next_12_months
        |--------------------------------------------------------------------------
        */
        'timeperiod'   => 'last_30_days',

        /*
        |--------------------------------------------------------------------------
        | Lead Stage filter condition - equal|not_equal
        |--------------------------------------------------------------------------
        */
        'stage_condition' => 'not_equal',

        /*
        |--------------------------------------------------------------------------
        | Lead Stage - 'stage1_name|stage2_name|stage3_name'
        |--------------------------------------------------------------------------
        */
        'stages' => null,
    ],

    'lead_pie_source'   => [

        /*
        |--------------------------------------------------------------------------
        | Lead Created Time - any|yesterday|today|tommorrow|last_month|current_month|next_month|
        |                     last_7_days|last_30_days|last_60_days|last_90_days|last_120_days|
        |                     last_6_months|last_12_months|next_7_days|next_30_days|next_60_days|
        |                     next_90_days|next_120_days|next_6_months|next_12_months
        |--------------------------------------------------------------------------
        */
        'timeperiod'   => 'last_30_days',

        /*
        |--------------------------------------------------------------------------
        | Lead Source filter condition - equal|not_equal
        |--------------------------------------------------------------------------
        */
        'source_condition' => '',

        /*
        |--------------------------------------------------------------------------
        | Lead Source - 'source1_name|source2_name|source3_name'
        |--------------------------------------------------------------------------
        */
        'sources' => null,
    ],

    'lead_stat'   => [

        /*
        |--------------------------------------------------------------------------
        | Number of active leads, converted leads & lost leads 
        | Reporting Period - any|yesterday|today|tommorrow|last_month|current_month|next_month|
        |                    last_7_days|last_30_days|last_60_days|last_90_days|last_120_days|
        |                    last_6_months|last_12_months|next_7_days|next_30_days|next_60_days|
        |                    next_90_days|next_120_days|next_6_months|next_12_months
        |--------------------------------------------------------------------------
        */
        'timeperiod'   => 'last_30_days',

    ],

    'lead_conversion'   => [

        /*
        |--------------------------------------------------------------------------
        | Lead Conversion Rate
        | Reporting Period - any|yesterday|today|tommorrow|last_month|current_month|next_month|
        |                    last_7_days|last_30_days|last_60_days|last_90_days|last_120_days|
        |                    last_6_months|last_12_months|next_7_days|next_30_days|next_60_days|
        |                    next_90_days|next_120_days|next_6_months|next_12_months
        |--------------------------------------------------------------------------
        */
        'timeperiod'   => 'last_30_days',

    ],

    'lost_lead_rate'   => [

        /*
        |--------------------------------------------------------------------------
        | Lost Lead Rate
        | Reporting Period - any|yesterday|today|tommorrow|last_month|current_month|next_month|
        |                    last_7_days|last_30_days|last_60_days|last_90_days|last_120_days|
        |                    last_6_months|last_12_months|next_7_days|next_30_days|next_60_days|
        |                    next_90_days|next_120_days|next_6_months|next_12_months
        |--------------------------------------------------------------------------
        */
        'timeperiod'   => 'last_30_days',

    ],

    'lead_conversion_timeline'   => [

        /*
        |--------------------------------------------------------------------------
        | Lead Conversion Rate per day
        | Reporting Period - any|yesterday|today|tommorrow|last_month|current_month|next_month|
        |                    last_7_days|last_30_days|last_60_days|last_90_days|last_120_days|
        |                    last_6_months|last_12_months|next_7_days|next_30_days|next_60_days|
        |                    next_90_days|next_120_days|next_6_months|next_12_months
        |--------------------------------------------------------------------------
        */
        'timeperiod'   => 'last_30_days',

    ],

    'lead_converted_leaderboard'   => [

        /*
        |--------------------------------------------------------------------------
        | Lead Converted Leaderboard
        | Reporting Period - any|yesterday|today|tommorrow|last_month|current_month|next_month|
        |                    last_7_days|last_30_days|last_60_days|last_90_days|last_120_days|
        |                    last_6_months|last_12_months|next_7_days|next_30_days|next_60_days|
        |                    next_90_days|next_120_days|next_6_months|next_12_months
        |--------------------------------------------------------------------------
        */
        'timeperiod'   => 'last_30_days',

    ],

);
