<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\User;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alumni = Alumni::with('user:id,email')->paginate(10); // Adjust the number as needed
        return view('admin.views.alumni.index', compact('alumni'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.views.alumni.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nim' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'prodi' => 'required|string|max:255',
            'tahun_lulus' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt(substr($request->nama, 0, 5) . $request->tahun_lulus),
        ]);
        $user->assignRole('alumni');

        $validatedData['user_id'] = $user->id;
        Alumni::create($validatedData);

        return redirect()->route('admin.alumni.index')->with('success', 'Alumni created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Alumni $alumni)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alumni $alumni)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alumni $alumni)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alumni $alumni)
    {
        //
    }
}
