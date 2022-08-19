<nav class='{!! $class['nav'] !!}'>
    <ul>  
        @permission('module.dashboard')      
            <li><a><i class='fa mdi mdi-view-dashboard'></i>Dashboard</a></li>
        @endpermission
        
        @permission('lead.view')
            <li class='{!! active_menu('admin.lead.') !!}'><a href='{!! route('admin.lead.index') !!}'><i class='fa mdi mdi-bullseye-arrow'></i>Leads</a></li>
        @endpermission

        @permission('contact.view')
            <li class='{!! active_menu('admin.contact.') !!}'><a href='{!! route('admin.contact.index') !!}'><i class='fa mdi mdi-account-circle'></i>Contacts</a></li>
        @endpermission

        @permission('account.view')
            <li class='{!! active_menu('admin.account.') !!}'><a href='{!! route('admin.account.index') !!}'><i class='fa mdi mdi-domain'></i>Accounts</a></li>
        @endpermission

        @permission('deal.view')    
            <li class='{!! active_menu('admin.deal.') !!}'><a href='{!! route('admin.deal.index') !!}'><i class='fa fa-handshake-o fa-sm'></i>Deals</a></li>
        @endpermission

        @permission('project.view')
            {{-- <li class='{!! active_menu('admin.project.') !!}'><a href='{!! route('admin.project.index') !!}'><i class='fa mdi mdi-library-books'></i>Projects</a></li> --}}
        @endpermission
        
        @permission('task.view')    
            {{-- <li class='{!! active_menu('admin.task.') !!}'><a href='{!! route('admin.task.index') !!}'><i class='fa fa-tasks'></i>Tasks</a></li> --}}
            <li class='{!! active_menu('admin.activity|admin.task') !!}'><a href='{!! route('admin.activity.index') !!}'><i class='fa mdi mdi-checkbox-multiple-marked'></i>Activities</a></li>
        @endpermission

        @permission('campaign.view')
            <li class='{!! active_menu('admin.campaign.') !!}'><a href='{!! route('admin.campaign.index') !!}'><i class='fa fa-bullhorn'></i>Campaigns</a></li>
        @endpermission

        @permission('module.sale')    
            <li class='{!! active_menu('admin.sale') !!}'>
                <a class='tree'><i class='fa mdi mdi-point-of-sale'></i>Sales<span class='fa fa-angle-left {!! active_menu_arrow('admin.sale') !!}'></span></a>
                <ul class='collapse' {!! active_tree('admin.sale', $class['nav']) !!}>
                    {{-- @permission('sale.sales_funnel')<li><a><i class='fa fa-circle-o'></i>Sales Funnel</a></li>@endpermission --}}
                    @permission('sale.estimate.view')<li><a href='{!! route('admin.sale-estimate.index') !!}' class='{!! active_menu('admin.sale-estimate.') !!}'><i class='fa fa-circle-o'></i>Estimates</a></li>@endpermission
                    @permission('sale.invoice.view')<li><a href='{!! route('admin.sale-invoice.index') !!}' class='{!! active_menu('admin.sale-invoice.') !!}'><i class='fa fa-circle-o'></i>Invoices</a></li>@endpermission
                    @permission('sale.item.view')<li><a href='{!! route('admin.sale-item.index') !!}' class='{!! active_menu('admin.sale-item.') !!}'><i class='fa fa-circle-o'></i>Items</a></li>@endpermission
                </ul>
            </li>   
        @endpermission
        
        @permission('module.finance')         
            <li class='{!! active_menu('admin.finance') !!}'>
                <a class='tree'><i class='fa mdi mdi-finance'></i>Finance<span class='fa fa-angle-left {!! active_menu_arrow('admin.finance') !!}'></span></a>
                <ul class='collapse' {!! active_tree('admin.finance', $class['nav']) !!}>
                    @permission('finance.payment.view')<li><a href='{!! route('admin.finance-payment.index') !!}' class='{!! active_menu('admin.finance-payment.') !!}'><i class='fa fa-circle-o'></i>Payments</a></li>@endpermission
                    @permission('finance.expense.view')<li><a href='{!! route('admin.finance-expense.index') !!}' class='{!! active_menu('admin.finance-expense.') !!}'><i class='fa fa-circle-o'></i>Expenses</a></li>@endpermission
                </ul>
            </li>
        @endpermission

        @permission('module.report')
            <li>
                <a class='tree'><i class='fa mdi mdi-chart-pie'></i>Reports<span class='fa fa-angle-left'></span></a>
                <ul class='collapse'>
                    @permission('report.campaign')<li><a><i class='fa fa-circle-o'></i>Campaigns</a></li>@endpermission
                    @permission('report.lead')<li><a><i class='fa fa-circle-o'></i>Leads</a></li>@endpermission            
                    @permission('report.account')<li><a><i class='fa fa-circle-o'></i>Accounts</a></li>@endpermission
                    @permission('report.project')<li><a><i class='fa fa-circle-o'></i>Projects</a></li>@endpermission
                    @permission('report.sale')<li><a><i class='fa fa-circle-o'></i>Sales</a></li>@endpermission
                    @permission('report.expense')<li><a><i class='fa fa-circle-o'></i>Expenses</a></li>@endpermission
                    @permission('report.expense_vs_income')<li><a><i class='fa fa-circle-o'></i>Expenses Vs. Income</a></li>@endpermission
                </ul>
            </li>
        @endpermission
        
        @permission('module.advanced')    
            <li class='{!! active_menu('admin.advanced') !!}'>
                <a class='tree'><i class='fa fa-plug'></i><strong class='more-menu'>more...</strong><span class='fa fa-angle-left {!! active_menu_arrow('admin.advanced') !!}'></span></a>
                <ul class='collapse' {!! active_tree('admin.advanced', $class['nav']) !!}>
                    <li><a href='{!! route('admin.event.index') !!}' class='{!! active_menu('admin.event.') !!}'><i class='fa fa-calendar-o'></i>Events</a></li>
                    @permission('advanced.goal.view')<li><a href='{!! route('admin.advanced-goal.index') !!}' class='{!! active_menu('admin.advanced-goal.') !!}'><i class='fa fa-crosshairs'></i>Goals</a></li>@endpermission
                    @permission('advanced.activity_log.view')<li><a href='{!! route('admin.advanced-activity-log.index') !!}' class='{!! active_menu('admin.advanced-activity-log.') !!}'><i class='fa fa-history'></i>History Log</a></li>@endpermission                    
                </ul>
            </li>
        @endpermission
        
        @if(permit('module.administration')  || permit('module.settings') || permit('module.custom_dropdowns') || permit('module.user') || permit('module.role'))        
            <li class='heading'>SETUP</li>
        @endif

        @if(permit('module.administration')  || permit('module.settings') || permit('module.custom_dropdowns'))
            <li class='{!! active_menu('admin.administration') !!}'>
                <a class='tree'><i class='fa fa-university'></i>Administration<span class='fa fa-angle-left {!! active_menu_arrow('admin.administration') !!}'></span></a>
                <ul class='collapse' {!! active_tree('admin.administration', $class['nav']) !!}>                
                    {{-- SETTINGS - Where To Go - Which Sub Module First - Then First Sub Module Link To Apply In <a href='First Sub Module Link'></a> --}}
                    @permission('module.settings')<li><a href='{!! route('admin.administration-setting.general') !!}' class='{!! active_menu('admin.administration-setting') !!}'><i class='fa fa-cogs'></i>Settings</a></li>@endpermission
                    {{-- CUSTOM DROPDOWNS - Where To Go - Which Sub Module First - Then First Sub Module Link To Apply --}}
                    @permission('module.custom_dropdowns')<li><a href='{!! route('admin.administration-dropdown-leadstage.index') !!}' class='{!! active_menu('admin.administration-dropdown') !!}'><i class='fa fa-chevron-circle-down'></i>Custom&nbsp;Dropdowns</a></li>@endpermission
                    
                    {{-- @permission('administration.manage_media')<li><a><i class='fa fa-folder'></i>Media</a></li>@endpermission --}}
                    @permission('administration.import')<li><a><i class='fa mdi mdi-database-plus'></i>Import</a></li>@endpermission
                    @permission('administration.export')<li><a><i class='fa mdi mdi-export'></i>Export</a></li>@endpermission
                    {{-- @permission('administration.database_backup')<li><a><i class='fa mdi mdi-database'></i>Database&nbsp;Backup</a></li>@endpermission --}}
                </ul>
            </li>
        @endpermission

        @permission('user.view')
            <li class='{!! active_menu('admin.user.') !!}'><a href='{!! route('admin.user.index') !!}'><i class='fa fa-users'></i>Users</a></li>
        @endpermission

        @permission('role.view')
            <li class='{!! active_menu('admin.role.') !!}'><a href='{!! route('admin.role.index') !!}'><i class='fa fa-universal-access'></i>Roles</a></li>
        @endpermission
    </ul>
</nav> <!-- end nav -->