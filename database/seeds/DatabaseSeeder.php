<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Property;
use App\PropertyImage;
use App\Installation;
use App\AgentClient;
use App\UserFavorite;
use App\Group;
use App\GroupSharing;
use App\GroupParticipation;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

        $d1 = new DateTime();

		//$this->call('UserTableSeeder');
        $this->command->info('------------- Total User  ...' . User::all()->count());

        //$this->call('InstallationTableSeeder');
        $this->command->info('------------- Total Installation  ...' . Installation::all()->count());

        //$this->call('AgentClientTableSeeder');
        $this->command->info('------------- Total AgentClient  ...' . AgentClient::all()->count());

        //$this->call('PropertyTableSeeder');
        $this->command->info('------------- Total Property  ...' . Property::all()->count());

        //$this->call('PropertyImageTableSeeder');
        $this->command->info('------------- Total Property Image  ...' . PropertyImage::all()->count());

        //$this->call('UserFavoriteTableSeeder');
        $this->command->info('------------- Total User Favorite  ...' . UserFavorite::all()->count());

        //$this->call('GroupTableSeeder');
        $this->command->info('------------- Total Group  ...' . Group::all()->count());

        //$this->call('GroupSharingTableSeeder');
        $this->command->info('------------- Total Group sharing  ...' . GroupSharing::all()->count());

        $this->call('GroupParticipationTableSeeder');
        $this->command->info('------------- Total Group Participation  ...' . GroupParticipation::all()->count());

        $d2 = new DateTime();
        $interval = $d2->getTimestamp() - $d1->getTimestamp();
        $this->command->info('---- Total Time taken : ' . $interval . 's');
	}

}
