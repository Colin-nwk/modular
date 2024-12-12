<?php

namespace Modules\UserManagement\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserManagemtController
{
    public function addRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        $user->assignRole($validated['role']);

        return back()->with('success', 'Role added successfully');
    }

    // Remove Role from User
    public function removeRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        $user->removeRole($validated['role']);

        return back()->with('success', 'Role removed successfully');
    }

    // Give Direct Permission to User
    public function givePermission(Request $request, User $user)
    {
        $validated = $request->validate([
            'permission' => 'required|exists:permissions,name'
        ]);

        $user->givePermissionTo($validated['permission']);

        return back()->with('success', 'Permission granted');
    }

    // Revoke Direct Permission
    public function revokePermission(Request $request, User $user)
    {
        $validated = $request->validate([
            'permission' => 'required|exists:permissions,name'
        ]);

        $user->revokePermissionTo($validated['permission']);

        return back()->with('success', 'Permission revoked');
    }
}
