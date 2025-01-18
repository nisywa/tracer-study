<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AlumniExport;
use App\Imports\AlumniImport;

class AlumniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
<<<<<<< Updated upstream
    public function create()
=======


     public function create()
>>>>>>> Stashed changes
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
<<<<<<< Updated upstream
        //
=======

        $validatedData = $request->validate([
            'nim' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_hp'  => 'required|string|max:15',
            'jenis_kelamin' => 'required|in:L,P',
            'prodi' => 'required|string|max:255',
            'tahun_lulus' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        $user = $alumni->user;
        $user->update([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt(substr($request->nama, 0, 5) . $request->tahun_lulus),
        ]);

        $alumni->update($validatedData);

        return redirect()->route('admin.alumni.index')->with('success', 'Alumni updated successfully.');
>>>>>>> Stashed changes
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alumni $alumni)
    {
        //
    }
    public function export()
    {
        return Excel::download(new AlumniExport, 'alumni.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new AlumniImport, $request->file('file'));

        return redirect()->route('admin.alumni.index')->with('success', 'Alumni imported successfully.');
    }
}
