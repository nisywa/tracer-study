<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SurveyBlockController extends Controller
{
    /**
     * Display a listing of blocks for a survey
     */
    public function index(Request $request, $surveyId)
    {
        $survey = Survey::findOrFail($surveyId);
        
        $query = SurveyBlock::query()->where('survey_id', $surveyId);
        
        if ($request->has('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('kode', 'like', $searchTerm)
                  ->orWhere('nama', 'like', $searchTerm);
            });
        }

        $blocks = $query->orderBy('urutan')
                       ->paginate(10)
                       ->appends($request->query());

        return view('admin.views.survey.blocks.index', compact('survey', 'blocks'));
    }

    /**
     * Store a newly created block
     */
    public function store(Request $request, $surveyId)
    {
        $validatedData = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Z0-9]+$/',
                function ($attribute, $value, $fail) use ($surveyId) {
                    if (SurveyBlock::where('survey_id', $surveyId)->where('kode', $value)->exists()) {
                        $fail('Kode blok sudah digunakan dalam survei ini.');
                    }
                },
            ],
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'urutan' => 'required|integer|min:1',
            'is_terminal' => 'boolean'
        ]);

        $validatedData['survey_id'] = $surveyId;
        $validatedData['is_terminal'] = $request->has('is_terminal');

        // Check if urutan already exists and shift if needed
        $existingBlock = SurveyBlock::where('survey_id', $surveyId)
                                   ->where('urutan', $validatedData['urutan'])
                                   ->first();
        
        if ($existingBlock) {
            // Shift other blocks
            SurveyBlock::where('survey_id', $surveyId)
                       ->where('urutan', '>=', $validatedData['urutan'])
                       ->increment('urutan');
        }

        SurveyBlock::create($validatedData);

        return redirect()->route('admin.survey.blocks.index', $surveyId)
                        ->with('success', 'Blok survei berhasil ditambahkan.');
    }

    /**
     * Update the specified block
     */
    public function update(Request $request, $surveyId, $id)
    {
        $block = SurveyBlock::where('survey_id', $surveyId)->findOrFail($id);
        
        $validatedData = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Z0-9]+$/',
                function ($attribute, $value, $fail) use ($surveyId, $id) {
                    if (SurveyBlock::where('survey_id', $surveyId)
                                   ->where('kode', $value)
                                   ->where('id', '!=', $id)
                                   ->exists()) {
                        $fail('Kode blok sudah digunakan dalam survei ini.');
                    }
                },
            ],
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'urutan' => 'required|integer|min:1',
            'is_terminal' => 'boolean'
        ]);

        $validatedData['is_terminal'] = $request->has('is_terminal');

        // Handle urutan changes
        if ($block->urutan != $validatedData['urutan']) {
            $this->reorderBlocks($surveyId, $block->urutan, $validatedData['urutan']);
        }

        $block->update($validatedData);

        return redirect()->route('admin.survey.blocks.index', $surveyId)
                        ->with('success', 'Blok survei berhasil diperbarui.');
    }

    /**
     * Remove the specified block
     */
    public function destroy($surveyId, $id)
    {
        try {
            DB::beginTransaction();
            
            $block = SurveyBlock::where('survey_id', $surveyId)->findOrFail($id);
            
            // Check if block has questions
            if ($block->hasQuestions()) {
                return redirect()->back()
                                ->with('error', 'Tidak dapat menghapus blok yang masih memiliki pertanyaan.');
            }
            
            // Check if block is target of any branch rules
            if ($block->incomingRules()->exists()) {
                return redirect()->back()
                                ->with('error', 'Tidak dapat menghapus blok yang masih menjadi target dari aturan percabangan.');
            }

            $urutan = $block->urutan;
            $block->delete();

            // Shift remaining blocks
            SurveyBlock::where('survey_id', $surveyId)
                       ->where('urutan', '>', $urutan)
                       ->decrement('urutan');

            DB::commit();
            
            return redirect()->route('admin.survey.blocks.index', $surveyId)
                            ->with('success', 'Blok survei berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return redirect()->back()
                            ->with('error', 'Terjadi kesalahan saat menghapus blok.');
        }
    }

    /**
     * Update blocks order via AJAX
     */
    public function reorder(Request $request, $surveyId)
    {
        $request->validate([
            'blocks' => 'required|array',
            'blocks.*.id' => 'required|exists:survey_blocks,id',
            'blocks.*.urutan' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->blocks as $blockData) {
                SurveyBlock::where('id', $blockData['id'])
                           ->where('survey_id', $surveyId)
                           ->update(['urutan' => $blockData['urutan']]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Urutan blok berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah urutan.'
            ], 422);
        }
    }

    /**
     * Get block details as JSON
     */
    public function show($surveyId, $id)
    {
        $block = SurveyBlock::with(['questions', 'incomingRules.sourceQuestion'])
                            ->where('survey_id', $surveyId)
                            ->findOrFail($id);

        return response()->json($block);
    }

    /**
     * Helper method to reorder blocks
     */
    private function reorderBlocks($surveyId, $oldUrutan, $newUrutan)
    {
        if ($oldUrutan == $newUrutan) return;

        if ($oldUrutan < $newUrutan) {
            // Moving down: shift blocks between old and new position up
            SurveyBlock::where('survey_id', $surveyId)
                       ->whereBetween('urutan', [$oldUrutan + 1, $newUrutan])
                       ->decrement('urutan');
        } else {
            // Moving up: shift blocks between new and old position down
            SurveyBlock::where('survey_id', $surveyId)
                       ->whereBetween('urutan', [$newUrutan, $oldUrutan - 1])
                       ->increment('urutan');
        }
    }
}
