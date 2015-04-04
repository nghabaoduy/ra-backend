<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Property;
use App\PropertyImage;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call('UserTableSeeder');
        $this->command->info('------------- Total User  ...' . User::all()->count());

        $this->call('PropertyTableSeeder');
        $this->command->info('------------- Total Property  ...' . Property::all()->count());

        $this->call('PropertyImageTableSeeder');
        $this->command->info('------------- Total Property Image  ...' . PropertyImage::all()->count());
	}

}
