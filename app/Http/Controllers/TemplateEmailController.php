<?php

namespace App\Http\Controllers;

use App\Models\TemplateEmail;
use App\Models\Survey;
use App\Services\SurveyEmailService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class TemplateEmailController extends Controller
{
    protected $emailService;

    public function __construct(SurveyEmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Display email templates page
     */
    public function template_email(): View
    {
        $templates = [
            'invitation' => $this->emailService->getTemplate('survey_invitation'),
            'reminder' => $this->emailService->getTemplate('survey_reminder'),
            'appreciation' => $this->emailService->getTemplate('survey_appreciation'),
        ];

        // Get active surveys for bulk email operations
        $surveys = Survey::whereDate('tanggal_selesai', '>=', now())->get();

        return view('admin.views.survey.template_email', compact('templates', 'surveys'));
    }

    /**
     * Update email template
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => 'required|in:survey_invitation,survey_reminder,survey_appreciation',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $success = $this->emailService->updateTemplate(
            $request->type,
            $request->subject,
            $request->body
        );

        if ($success) {
            return redirect()->back()->with('success', 'Template email berhasil diperbarui!');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui template email!');
        }
    }

    /**
     * Preview email template
     */
    public function preview(Request $request)
    {
        $request->validate([
            'type' => 'required|in:survey_invitation,survey_reminder,survey_appreciation',
        ]);

        $template = $this->emailService->getTemplate($request->type);
        
        if (!$template) {
            return response()->json(['error' => 'Template tidak ditemukan'], 404);
        }

        // Sample data for preview
        $sampleData = [
            '{{name}}' => 'John Doe',
            '{{email}}' => 'john.doe@example.com',
            '{{password}}' => 'password123',
            '{{survey_name}}' => 'Survei Tracer Study 2025',
            '{{start_date}}' => '2025-01-01',
            '{{end_date}}' => '2025-12-31',
            '{{login_url}}' => route('login'),
        ];

        $previewSubject = str_replace(array_keys($sampleData), array_values($sampleData), $template->subject);
        $previewBody = str_replace(array_keys($sampleData), array_values($sampleData), $template->body);

        return response()->json([
            'subject' => $previewSubject,
            'body' => $previewBody,
        ]);
    }
}
