<?php

namespace App\Providers;

use Route;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);

        Route::bind('user', function($id) 
        { 
            $user = \App\Models\Staff::withTrashed()->find($id);
            return isset($user) ? $user : abort('404');
        });
        
        Route::model('role', \App\Models\Role::class);
        Route::model('lead', \App\Models\Lead::class);
        Route::model('source', \App\Models\Source::class);
        Route::model('leadstage', \App\Models\LeadStage::class);
        Route::model('contact', \App\Models\Contact::class);
        Route::model('contacttype', \App\Models\ContactType::class);
        Route::model('account', \App\Models\Account::class);
        Route::model('accounttype', \App\Models\AccountType::class);
        Route::model('industrytype', \App\Models\IndustryType::class);
        Route::model('sale-item', \App\Models\Item::class);
        Route::model('sale-estimate', \App\Models\Estimate::class);
        Route::model('sale-invoice', \App\Models\Invoice::class);
        Route::model('project', \App\Models\Project::class);
        Route::model('task', \App\Models\Task::class);
        Route::model('taskstatus', \App\Models\TaskStatus::class);
        Route::model('filterview', \App\Models\FilterView::class);
        Route::model('note', \App\Models\Note::class);
        Route::model('attachfile', \App\Models\AttachFile::class);
        Route::model('paymentmethod', \App\Models\PaymentMethod::class);
        Route::model('expensecategory', \App\Models\ExpenseCategory::class);
        Route::model('finance-expense', \App\Models\Expense::class);
        Route::model('finance-payment', \App\Models\Payment::class);
        Route::model('lead-score-rule', \App\Models\LeadScoreRule::class);
        Route::model('campaign', \App\Models\Campaign::class);
        Route::model('campaigntype', \App\Models\CampaignType::class);
        Route::model('deal', \App\Models\Deal::class);
        Route::model('dealtype', \App\Models\DealType::class);
        Route::model('dealstage', \App\Models\DealStage::class);
        Route::model('dealpipeline', \App\Models\DealPipeline::class);
        Route::model('goal', \App\Models\Goal::class);
        Route::model('event', \App\Models\Event::class);
        Route::model('call', \App\Models\Call::class);
        Route::model('chatroom', \App\Models\ChatRoom::class);
        Route::model('currency', \App\Models\Currency::class);
        Route::model('activity', \App\Models\Activity::class);
        Route::model('revision', \App\Models\Revision::class);        
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $this->mapWebRoutes($router);

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapWebRoutes(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace, 'middleware' => 'web',
        ], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
