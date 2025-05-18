<?php

namespace App\Http\Controllers;

use App\Models\Atasan;
use App\Models\Alumni;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AtasanExport;
use App\Imports\AtasanImport;

class AtasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Atasan::with('user:id,email');

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nip', 'like', '%' . $searchTerm . '%');
            });
        }

        $dataAtasan = $query->paginate(10)->withQueryString();

        return view('admin.views.atasan.index', compact('dataAtasan'));
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
            'nip' => 'required|string|max:255',
            'email'=> 'required|string|email',
            'jabatan' => 'required|string|max:255',
            'satuan_kerja' => 'required|string|max:255',
            'unit_kerja' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
        ]);

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            // 'password' => bcrypt(substr($request->nama, 0, 5) . substr($request->no_hp,offset: 0,length: 5)),
            'password'=> bcrypt('password'),
            'role' => 'atasan',
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
            'nip' => 'required|string|max:255',
            'email'=> 'required|string|email',
            'jabatan' => 'required|string|max:255',
            'satuan_kerja' => 'required|string|max:255',
            'unit_kerja' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
        ]);

        $user = $atasan->user;
        $user->update([
            'name' => $request->nama,
            'email' => $request->email,
            'password'=>bcrypt(substr($request->nip, 0, 5)),
        ]);

        $validatedData['user_id'] = $user->id;
        $atasan->update($validatedData);

        return redirect()->route('admin.atasan.index')->with('success', 'Atasan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Atasan $atasan)
    {
        var_dump($atasan);
        $atasan->user->delete();
        $atasan->delete();

        return redirect()->route('admin.atasan.index')->with('success', 'Atasan deleted successfully.');
    }


    public function export(Excel $excel)
    {
        return Excel::download(new AtasanExport, 'atasan.xlsx');
    }

    public function import(Request $request, Excel $excel)
    {
        try{
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv',
            ]);
            Excel::import(new AtasanImport, $request->file('file'));

            return redirect()->route('admin.atasan.index')->with('success', 'Atasan imported successfully.');
        }catch(\Exception $e){
            return redirect()->route('admin.atasan.index')->with('error', 'Failed to import atasan.');
        }   
    }

    public function details($id)
    {
        $atasan = Atasan::findOrFail($id);
        $alumni = Alumni ::where('nip_kepala_bps', $atasan->nip)->paginate(10);
        return view('admin.views.atasan.details', compact('atasan', 'alumni'));

    }
}
