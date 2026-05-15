<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index()
{
    $users = User::latest()->get();

    return view('users.index', compact('users'));
}

    /**
     * Show the form for creating a new resource.
     */
public function create()
{
    return view('users.create');
}

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required',
        'username' => 'required|unique:users',
        'email' => 'required|email|unique:users',
        'password' => 'required',
        'role' => 'required',
       'photo' => 'nullable|image|max:5120'
    ]);

    if ($request->hasFile('photo')) {

        $data['photo'] = $request
            ->file('photo')
            ->store('users', 'public');

    }

    $data['password'] = bcrypt($data['password']);

    $data['active'] = $request->has('active');

    User::create($data);

    return redirect()
        ->route('users.index')
        ->with('success', 'Usuario creado');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
 public function edit(User $user)
{
    return view('users.edit', compact('user'));
}

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, User $user)
{
    $data = $request->validate([
        'name' => 'required',
        'username' => 'required|unique:users,username,' . $user->id,
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role' => 'required',
        'photo' => 'nullable|image'
    ]);

    if ($request->hasFile('photo')) {

        $data['photo'] = $request
            ->file('photo')
            ->store('users', 'public');

    }

    if ($request->password) {

        $data['password'] = bcrypt($request->password);

    }

    $data['active'] = $request->has('active');

    $user->update($data);

    return redirect()
        ->route('users.index')
        ->with('success', 'Usuario actualizado');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
