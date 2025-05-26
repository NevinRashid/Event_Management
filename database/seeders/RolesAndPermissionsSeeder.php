<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // add Permissions
        $permissions = [
            'create user',
            'edit user',
            'view user',
            'delete user',
            'create event',
            'edit event',
            'view event',
            'delete event',
            'create location',
            'edit location',
            'view location',
            'delete location',
            'create event-type',
            'edit event-type',
            'view event-type',
            'delete event-type',
            'upload image',
            'update image',
            'view image',
            'delete image',
            'create reservation',
            'edit reservation',
            'view reservation',
            'delete reservation',
            'assign role',
            'create role',
            'edit role',
            'delete role',
            'get reservations',
            'register',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]); 
        }

        // add roles
        $superAdmin=Role::firstOrCreate(['name' => 'super admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $organizer = Role::firstOrCreate(['name' => 'organizer']);
        $attendee = Role::firstOrCreate(['name' => 'attendee']);
        $guest = Role::firstOrCreate(['name' => 'guest']);

        $superAdmin->givePermissionTo($permissions);

        $admin->givePermissionTo([
            'edit user',
            'view user',
            'delete user',
            'create user',
            'create event',
            'edit event',
            'view event',
            'delete event',
            'create location',
            'edit location',
            'view location',
            'delete location',
            'create event-type',
            'edit event-type',
            'view event-type',
            'delete event-type',
            'upload image',
            'update image',
            'view image',
            'delete image',
            'create reservation',
            'edit reservation',
            'view reservation',
            'delete reservation',
            'assign role',
            'get reservations',
            'register',
        ]);

        $organizer->givePermissionTo([
            'create user',
            'edit user',
            'view user',
            'delete user',
            'edit event',
            'view event',
            'view location',
            'create location',
            'edit location',
            'view event-type',
            'upload image',
            'view image',
            'update image',
            'delete image',
            'create reservation',
            'edit reservation',
            'view reservation',
            'delete reservation',
            'get reservations',
            'register',
        ]);

        $attendee->givePermissionTo([
            'view event',
            'create reservation',
            'edit reservation',
            'view reservation',
            'delete reservation',
            'register',
        ]);
        
        $guest->givePermissionTo([
            'view event',
            'register',
        ]);

    }
}
