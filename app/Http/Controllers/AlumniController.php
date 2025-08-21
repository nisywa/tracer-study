<?php

namespace App\Http\Controllers;

use App\Exports\AlumniExport;
use App\Imports\AlumniImport;
use App\Models\Alumni;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class AlumniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Alumni::with('user:id,email');

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nip', 'like', '%' . $searchTerm . '%');
            });
        }

        $daftarAlumni = $query->paginate(10)->withQueryString();

        return view('admin.views.alumni.index', compact('daftarAlumni'));
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
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:255',
            'email' => 'required|string|email',
            'jabatan' => 'nullable|string|max:255',
            'satuan_kerja' => 'nullable|string|max:255',
            'unit_kerja'  => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:15',
            'nip_kepala_bps' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date_format:Y-m-d',
            'tahun_lulus' => 'nullable|string|max:255',
        ]);

        // Generate password: 2 angka terakhir NIP + 2 huruf terakhir nama + 2 angka terakhir tanggal lahir
        $nipLast2 = substr($request->nip, -2);
        $namaLast2 = strtolower(substr(preg_replace('/[^A-Za-z]/', '', $request->nama), -2));
        $tanggalLahir = str_replace('-', '', $request->tanggal_lahir ?? '01');
        $tanggalLast2 = substr($tanggalLahir, -2);
        $password = $nipLast2 . $namaLast2 . $tanggalLast2;

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'role' => 'alumni',
            'password' => bcrypt($password),
        ]);
        $user->assignRole('alumni');

        $validatedData['user_id'] = $user->id;
        Alumni::create($validatedData);

        return redirect()->route('admin.alumni.index')->with('success', 'Alumni created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Alumni $alumnus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alumni $alumnus)
    {
        return view('admin.views.alumni.edit', compact('alumnus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alumni $alumnus)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:255',
            'email' => 'required|string|email',
            'jabatan' => 'nullable|string|max:255',
            'satuan_kerja' => 'nullable|string|max:255',
            'unit_kerja'  => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:15',
            'nip_kepala_bps' => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date_format:Y-m-d',
            'tahun_lulus' => 'nullable|string|max:255',
        ]);

        $user = $alumnus->user;
        $user->update([
            'name' => $request->nama,
            'email' => $request->email,
        ]);

        $validatedData['user_id'] = $user->id;
        $alumnus->update($validatedData);

        return redirect()->route('admin.alumni.index')->with('success', 'Alumni updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alumni $alumnus)
    {
        //var_dump($alumnus);
        $alumnus->user->delete();
        $alumnus->delete();

        return redirect()->route('admin.alumni.index')->with('success', 'Alumni deleted successfully.');
    }

    public function export(Excel $excel)
    {
        return $excel->download(new AlumniExport, 'alumni.xlsx');
    }

    public function import(Request $request, Excel $excel)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv',
            ]);

            $excel->import(new AlumniImport, $request->file('file'));

            return redirect()->route('admin.alumni.index')->with('success', 'Alumni imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.alumni.index')->with('error', 'Failed to import alumni.');
        }
    }


}
