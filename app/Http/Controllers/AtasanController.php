<?php

namespace App\Http\Controllers;

use App\Models\Atasan;
use App\Models\User;
use Illuminate\Http\Request;

class AtasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $atasan = Atasan::with('user:id,email')->paginate(10);
        return view('admin.views.atasan.index',compact('atasan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.views.atasan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'email'=> 'required|string|email',
            'jabatan' => 'required|string|max:255',
            'satuan_kerja' => 'required|string|max:255',
            'unit_kerja' => 'required|string|max:255',
            'alamat_kantor' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
        ]);

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt(substr($request->nama, 0, 5) . substr($request->no_hp,offset: 0,length: 5)),
        ]);
        $user->assignRole('atasan');

        $validatedData['user_id'] = $user->id;
        Atasan::create($validatedData);

        return redirect()->route('admin.atasan.index')->with('success', 'Atasan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Atasan $atasan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Atasan $atasan)
    {
        return view('admin.views.atasan.edit',compact('atasan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Atasan $atasan)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'email'=> 'required|string|email',
            'jabatan' => 'required|string|max:255',
            'satuan_kerja' => 'required|string|max:255',
            'unit_kerja' => 'required|string|max:255',
            'alamat_kantor' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
        ]);

        $user = User::update([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt(substr($request->nama, 0, 5) . substr($request->no_hp,offset: 0,length: 5)),
        ]);
        

        $validatedData['user_id'] = $user->id;
        Atasan::update($validatedData);

        return redirect()->route('admin.atasan.index')->with('success', 'Atasan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Atasan $atasan)
    {
        //
    }
}
