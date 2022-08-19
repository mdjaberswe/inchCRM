<?php

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectContactTableSeeder extends Seeder
{
	public function run()
	{
		\DB::table('project_contact')->truncate();

		$projects = Project::all();

		foreach($projects as $project) :
			$contacts = $project->account->contacts->pluck('id')->toArray();
			$project->contacts()->attach($contacts);
		endforeach;
	}
}
