<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Channel;


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

if (auth()->user()->role === 'admin') {

    $channels = Channel::all();

} else {

    $channels = auth()->user()->channels;

}

    return view('users.create', compact('channels'));

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

        'photo' => 'nullable|image|max:5120',

        'channels' => 'nullable|array',

        'channels.*' => 'exists:channels,id'

    ]);

    // FOTO
    if ($request->hasFile('photo')) {

        $data['photo'] = $request
            ->file('photo')
            ->store('users', 'public');

    }

    // PASSWORD
    $data['password'] = bcrypt($data['password']);

    // ACTIVO
    $data['active'] = $request->has('active');

    // CREAR USUARIO
    $user = User::create($data);

    // GUARDAR CANALES
    $user->channels()->sync($request->channels ?? []);

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
    $channels = Channel::all();

    return view('users.edit', compact(
        'user',
        'channels'
    ));
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
        'channels' => 'nullable|array',
'channels.*' => 'exists:channels,id',
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
    $user->channels()->sync($request->channels ?? []);

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
