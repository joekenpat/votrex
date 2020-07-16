<?php

use App\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $schools = [
      ['name' => 'University Of Port Harcourt', 'type' => 'university', 'state' => 'Rivers'],
      ['name' => 'Rivers State University', 'type' => 'university', 'state' => 'Rivers'],
      ['name' => 'City Model University', 'type' => 'university', 'state' => 'Rivers'],
      ['name' => 'Rivers State Polytechnic Bori', 'type' => 'polytechnic', 'state' => 'Rivers'],
      ['name' => 'Captain Elechi Amadi Polytechnic', 'type' => 'polytechnic', 'state' => 'Rivers'],
      ['name' => 'Federal College of Education', 'type' => 'college', 'state' => 'Rivers'],
      ['name' => 'Ignatius Ajuru College of Education', 'type' => 'college', 'state' => 'Rivers'],
      ['name' => 'River State College of Art & Technology', 'type' => 'college', 'state' => 'Rivers'],
      ['name' => 'Institute Petroleum Studies', 'type' => 'college', 'state' => 'Rivers'],
      ['name' => 'Offshore Technology Institute Nigeria', 'type' => 'college', 'state' => 'Rivers'],
    ];
    $schoolsProgressBar = $this->command->getOutput()->createProgressBar(count($schools));
    foreach ($schools as $school) {
      School::create($school);
      $schoolsProgressBar->advance();
    }
    $schoolsProgressBar->finish();
  }
}
