<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['location'])->withCount('orders');

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function toggleBlock($id)
    {
        $user = User::findOrFail($id);

        $user->status = ($user->status == 'active') ? 'blocked' : 'active';
        $user->save();

        $message = $user->status == 'blocked' ? 'Ulanyjy bloklandy.' : 'Ulanyjy aktiw edildi.';
        return back()->with('success', $message);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'role'     => 'required|in:admin,operator,client',
            'email'    => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
            'status'   => 'required|in:active,blocked',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->status = $request->status;
        $user->phone = $request->phone;
        $user->admin_notes = $request->admin_notes;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $permissions = [];
        if ($request->role !== 'client' && $request->has('permissions')) {
            foreach ($request->input('permissions') as $key => $value) {
                $permissions[$key] = true;
            }
        }
        $user->permissions = $permissions;

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Changes saved successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() == $user->id) {
            return back()->with('error', 'Özüňizi öçürip bilmersiňiz!');
        }

        $user->delete();
        return back()->with('success', 'Ulanyjy öçürildi.');
    }
}
