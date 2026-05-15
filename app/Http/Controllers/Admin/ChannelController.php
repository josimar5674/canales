<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Channel;


class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index()
{
    $channels = Channel::latest()->get();

    return view('channels.index', compact('channels'));
}

    /**
     * Show the form for creating a new resource.
     */
  public function create()
{
    return view('channels.create');
}

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|unique:channels'
    ]);

    $data['active'] = $request->has('active');
    $data['created_by'] = auth()->id();

    Channel::create($data);

    return redirect()
        ->route('channels.index');
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
   public function edit(Channel $channel)
{
    return view('channels.edit', compact('channel'));
}

    /**
     * Update the specified resource in storage.
     */
  public function update(Request $request, Channel $channel)
{
    $data = $request->validate([
        'name' => 'required|unique:channels,name,' . $channel->id
    ]);

    $data['active'] = $request->has('active');

    $channel->update($data);

    return redirect()
        ->route('channels.index');
}

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Channel $channel)
{
    $channel->delete();

    return back();
}
}
