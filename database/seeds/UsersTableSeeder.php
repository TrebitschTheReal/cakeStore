<?php

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dev_permission = Permission::where('slug','create-tasks')->first();
        $manager_permission = Permission::where('slug', 'edit-users')->first();
        $default_permission = Permission::where('slug', 'reg')->first();

        //RoleTableSeeder.php
        $dev_role = new Role();
        $dev_role->slug = 'admin';
        $dev_role->name = 'Site admin';
        $dev_role->save();
        $dev_role->permissions()->attach($dev_permission);

        $default_role = new Role();
        $default_role->slug = 'reg';
        $default_role->name = 'Regisztrált felhasználó';
        $default_role->save();
        $default_role->permissions()->attach($default_permission);

        $manager_role = new Role();
        $manager_role->slug = 'manager';
        $manager_role->name = 'Manager';
        $manager_role->save();
        $manager_role->permissions()->attach($manager_permission);

        $dev_role = Role::where('slug','admin')->first();
        $manager_role = Role::where('slug', 'manager')->first();
        $default_role = Permission::where('slug', 'reg')->first();

        $createTasks = new Permission();
        $createTasks->slug = 'create-tasks';
        $createTasks->name = 'Create Tasks';
        $createTasks->save();
        $createTasks->roles()->attach($dev_role);

        $reg = new Permission();
        $reg->slug = 'reg';
        $reg->name = 'reg';
        $reg->save();
        $reg->roles()->attach($default_role);

        $editUsers = new Permission();
        $editUsers->slug = 'edit-users';
        $editUsers->name = 'Edit Users';
        $editUsers->save();
        $editUsers->roles()->attach($manager_role);

        $dev_role = Role::where('slug','admin')->first();
        $manager_role = Role::where('slug', 'manager')->first();
        $dev_perm = Permission::where('slug','create-tasks')->first();
        $manager_perm = Permission::where('slug','edit-users')->first();

        $reg_role = Role::where('slug', 'reg')->first();
        $reg_perm = Permission::where('slug', 'reg')->first();

        $admin = new User();
        $admin->name = 'Admin József';
        $admin->email = 'admin-mail@email.com';
        $admin->password = bcrypt('secret');
        $admin->save();
        $admin->roles()->attach($dev_role);
        $admin->permissions()->attach($dev_perm);

        $manager = new User();
        $manager->name = 'Kelekotya Abigél';
        $manager->email = 'manager-mail@email.com';
        $manager->password = bcrypt('secret');
        $manager->save();
        $manager->roles()->attach($manager_role);
        $manager->permissions()->attach($manager_perm);

        $manager = new User();
        $manager->name = 'Gipsz Jakab';
        $manager->email = 'asd@gmail.com';
        $manager->password = bcrypt('secret');
        $manager->save();
        $manager->roles()->attach($reg_role);
        $manager->permissions()->attach($reg_perm);

        $manager = new User();
        $manager->name = 'John Doe';
        $manager->email = 'manageasdasdr@gmail.com';
        $manager->password = bcrypt('secret');
        $manager->save();
        $manager->roles()->attach($reg_role);
        $manager->permissions()->attach($reg_perm);

        $manager = new User();
        $manager->name = 'Random János';
        $manager->email = 'manasdasdager@gmail.com';
        $manager->password = bcrypt('secret');
        $manager->save();
        $manager->roles()->attach($reg_role);
        $manager->permissions()->attach($reg_perm);

        $manager = new User();
        $manager->name = 'Admin';
        $manager->email = 'a@a';
        $manager->password = bcrypt('a');
        $manager->save();
        $manager->roles()->attach($dev_role);
        $manager->permissions()->attach($dev_perm);

        $manager = new User();
        $manager->name = 'Manager';
        $manager->email = 'b@b';
        $manager->password = bcrypt('b');
        $manager->save();
        $manager->roles()->attach($manager_role);
        $manager->permissions()->attach($manager_perm);

        $manager = new User();
        $manager->name = 'Reg user';
        $manager->email = 'c@c';
        $manager->password = bcrypt('c');
        $manager->save();
        $manager->roles()->attach($reg_role);
        $manager->permissions()->attach($reg_perm);
    }
}
