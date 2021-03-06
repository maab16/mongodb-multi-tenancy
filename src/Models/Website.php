<?php

namespace Codexshaper\Tenancy\Models;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Website extends Eloquent
{
   public function createWebsite( $website )
   {
   		DB::purge('mongodb');
   		// Make sure to use the database name we want to establish a connection.
   		Config::set('database.connections.mongodb.host', env('DB_HOST', 'localhost'));
   		Config::set('database.connections.mongodb.database', $website);
   		Config::set('database.connections.mongodb.username', env('DB_USERNAME', ''));
   		Config::set('database.connections.mongodb.password', env('DB_PASSWORD', ''));
   		  
   		DB::reconnect('mongodb');
   		  
   		Schema::connection('mongodb')->getConnection()->reconnect();

   		Artisan::call('migrate', [
   		    '--path' => 'database/migrations/tenant',
   		    '--force'     => true,
   		]);
   }

   public static function migrate( $website )
   {
   		$this->createWebsite( $website );
   }
}
