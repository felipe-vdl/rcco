<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Victor Mussel Candido',
                'email' => 'victor.mussel@hotmail.com',
                'nivel' => 'Super-Admin',
                'password' => bcrypt('teste123'), //'$2y$10$eMMXLkP579E/hf8.oSBJRu.yndQDIU0XrjRsY/R9Sr6hxzjToy0gC'
            ],
            [
                'name' => 'Felipe Vidal',
                'email' => 'felipe.vidal.mesquita@gmail.com',
                'nivel' => 'Super-Admin',
                'password' => bcrypt('teste123'), //'$2y$10$eMMXLkP579E/hf8.oSBJRu.yndQDIU0XrjRsY/R9Sr6hxzjToy0gC'
            ]
        ]
    );
    }
}
