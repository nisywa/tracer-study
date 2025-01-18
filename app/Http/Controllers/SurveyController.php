<?php

namespace App\Http\Controllers;

use App\Models\survey;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $survey = Survey::all();
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
        return redirect()->route('admin.views.survey.index')->with('success','Survey created successfully.');
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
    public function edit(survey $survey)
    {
        return view('admin.views.survey.edit',compact('survey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, survey $survey)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'required|integer|max:8',
            'tanggal_selesai' => 'required|integer|max:8',
            'deskripsi' => 'required|string|max:255',
        ]);

        Survey::update($validatedData);

        return redirect()->route('admin.views.survey.index')->with('success', 'Survey updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(survey $survey)
    {
        //
    }
}
