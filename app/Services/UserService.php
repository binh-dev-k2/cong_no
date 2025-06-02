<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function filterDatatable(array $data)
    {
        $pageNumber = ($data['start'] ?? 0) / ($data['length'] ?? 1) + 1;
        $pageLength = $data['length'] ?? 10;
        $skip = ($pageNumber - 1) * $pageLength;

        $query = User::query();

        if (isset($data['search'])) {
            $search = $data['search'];
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        $query->orderBy('id', 'desc');
        $recordsFiltered = $recordsTotal = $query->count();
        $businnesses = $query
            ->with('roles')
            ->skip($skip)
            ->take($pageLength)
            ->get();

        return [
            "draw" => $data['draw'] ?? 1,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            'data' => $businnesses
        ];
    }

    public function create($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole($data['role_name']);

        return $user;
    }

    public function updateRole($data)
    {
        $user = User::findOrFail($data['id']);
        $user->syncRoles($data['role_name']);
        return $user;
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }

    public function updatePasswrod($data)
    {
        $user = Auth::user();
        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($data['current_password'], $user->password)) {
            return false;
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($data['password']);
        $user->save();

        return true;
    }

    public function getAll()
    {
        return User::all();
    }
}
