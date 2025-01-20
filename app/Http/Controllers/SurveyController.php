<?php

namespace App\Http\Controllers;

use App\Models\survey;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $survey = Survey::paginate(10);
        foreach ($survey as $srvy) {
           $srvy->tanggal_mulai = Carbon::parse($srvy->tanggal_mulai)->format("d-m-y");
           $srvy->tanggal_selesai = Carbon::parse($srvy->tanggal_selesai)->format("d-m-y");
           if($srvy->tanggal_selesai >= now()){
            $srvy->status="Aktif";
           }else{
            $srvy->status="Selesai";
           }
        }
        return view('admin.views.survey.index',compact('survey'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $survey = Survey::paginate(10);
        return view('admin.views.survey.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'deskripsi' => 'required|string|max:255',
        ]);

        Survey::create($validatedData);
        return redirect()->route('admin.survey.index')->with('success','Survey created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(survey $survey)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $survey = Survey::findOrFail($id);
        return view('admin.views.survey.edit', ['survey'=> $survey]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'required|integer|max:8',
            'tanggal_selesai' => 'required|integer|max:8',
            'deskripsi' => 'required|string|max:255',
        ]);
        $survey = Survey::findOrFail($id);
        $survey->update($validatedData);

        return redirect()->route('admin.survey.index')->with('success', 'Survey updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(survey $survey)
    {
        //
    }
}
