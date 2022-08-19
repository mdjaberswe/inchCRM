<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $this->call(CountriesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(RoleBooksTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(RoleUserTableSeeder::class);
        $this->call(StaffsTableSeeder::class);
        $this->call(AccountTypesTableSeeder::class);
        $this->call(AccountsTableSeeder::class);
        $this->call(ContactTypesTableSeeder::class);
        $this->call(IndustryTypesTableSeeder::class);
        $this->call(ContactsTableSeeder::class);
        $this->call(SourcesTableSeeder::class);
        $this->call(LeadStagesTableSeeder::class);
        $this->call(LeadsTableSeeder::class);
        $this->call(SocialMediaTableSeeder::class);
        $this->call(ItemsTableSeeder::class);
        $this->call(CartItemsTableSeeder::class);
        $this->call(EstimatesTableSeeder::class);
        $this->call(InvoicesTableSeeder::class);
        $this->call(ItemSheetsTableSeeder::class);
        $this->call(ProjectsTableSeeder::class);
        $this->call(ProjectMemberTableSeeder::class);
        $this->call(ProjectContactTableSeeder::class);
        $this->call(MilestonesTableSeeder::class);
        $this->call(TasksTableSeeder::class);
        $this->call(TaskStatusTableSeeder::class);
        $this->call(ExpenseCategoriesTableSeeder::class);
        $this->call(ExpensesTableSeeder::class);
        $this->call(PaymentMethodsTableSeeder::class);
        $this->call(PaymentsTableSeeder::class);
        $this->call(CampaignTypesTableSeeder::class);
        $this->call(CampaignsTableSeeder::class);
        $this->call(CampaignMembersTableSeeder::class);
        $this->call(DealTypesTableSeeder::class);
        $this->call(DealStagesTableSeeder::class);
        $this->call(DealPipelinesTableSeeder::class);
        $this->call(DealsTableSeeder::class);
        $this->call(GoalsTableSeeder::class);
        $this->call(EventsTableSeeder::class);
        $this->call(EventAttendeesTableSeeder::class);
        $this->call(RemindersTableSeeder::class);
        $this->call(NotificationCasesTableSeeder::class);
        $this->call(NotificationInfosTableSeeder::class);
        $this->call(NotificationsTableSeeder::class);        
        $this->call(ChatRoomsTableSeeder::class);
        $this->call(ChatRoomMembersTableSeeder::class);
        $this->call(ChatSendersTableSeeder::class);
        $this->call(ChatReceiversTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(AllowedStaffsTableSeeder::class);
        $this->call(LeadScoresTableSeeder::class);
        $this->call(LeadScoreRulesTableSeeder::class);
        $this->call(NoteInfosTableSeeder::class);
        $this->call(NotesTableSeeder::class);
        $this->call(AttachFilesTableSeeder::class);
        $this->call(CallsTableSeeder::class);
        $this->call(ActivitiesTableSeeder::class);
        $this->call(FilterViewsTableSeeder::class);
        \DB::table('revisions')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
