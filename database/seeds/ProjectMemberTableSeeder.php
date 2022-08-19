<?php

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectMemberTableSeeder extends Seeder
{
	public function run()
	{
		\DB::table('project_member')->truncate();

		$projects = Project::all();

		foreach($projects as $project) :
			$project->members()->attach([$project->project_owner]);
		endforeach;
	}
}
