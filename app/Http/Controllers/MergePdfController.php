<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;

class MergePdfController extends Controller
{
    public function merge(Request $request)
    {
        $request->validate([
            'files' => 'required|array|min:2',
            'files.*' => 'required|file|mimes:pdf',
        ]);

        $uploadedFiles = $request->file('files');

        // Save uploads to temporary files
        $tempPaths = [];
        foreach ($uploadedFiles as $file) {
            $tmp = tempnam(sys_get_temp_dir(), 'laravel_pdf_') . '.pdf';
            $moved = $file->move(dirname($tmp), basename($tmp));
            if (! $moved || ! file_exists($tmp)) {
                // log and return immediately if move failed
                Log::error('Failed to move uploaded PDF to temp', ['origName' => $file->getClientOriginalName(), 'tmp' => $tmp]);
                return response()->json(['error' => 'Failed to save uploaded file: '.$file->getClientOriginalName()], 500);
            }
            $tempPaths[] = $tmp;
        }

        $mergedTmp = tempnam(sys_get_temp_dir(), 'merged_pdf_') . '.pdf';

        try {
            $pdf = new Fpdi();
            $pdf->SetAutoPageBreak(false);

            foreach ($tempPaths as $path) {
                $pageCount = $pdf->setSourceFile($path);

                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    $tplId = $pdf->importPage($pageNo);
                    $size = $pdf->getTemplateSize($tplId);

                    $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [
                        $size['width'],
                        $size['height']
                    ]);

                    $pdf->useTemplate($tplId);
                }
            }

            ob_end_clean();

            // IMPORTANT: Correct argument order
            $pdf->Output('F', $mergedTmp);


            // return file for fetch as binary
            return response()->download($mergedTmp, 'merged.pdf', [
                'Content-Type' => 'application/pdf'
            ])->deleteFileAfterSend(true);

        } catch (\Throwable $e) {
            Log::error('PDF merge failed: '.$e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Failed to merge PDFs: '.$e->getMessage()], 500);
        } finally {
            foreach ($tempPaths as $p) {
                if (file_exists($p)) {
                    @unlink($p);
                }
            }
        }
    }

    public function preview(Request $request)
    {
        $request->validate([
            'files' => 'required|array|min:2', // at least 2 files
            'files.*' => 'required|file|mimes:pdf|max:10240'
        ]);

        $uploadedFiles = $request->file('files');
        $tempPaths = [];

        // Move files to temp folder
        foreach ($uploadedFiles as $file) {
            $tmp = tempnam(sys_get_temp_dir(), 'laravel_pdf_');
            $movedPath = $file->move(sys_get_temp_dir(), basename($tmp) . '.pdf');
            $tempPaths[] = $movedPath->getRealPath();

        }

        try {
            $pdf = new Fpdi();

            foreach ($tempPaths as $path) {
                $pageCount = $pdf->setSourceFile($path);
                for ($i = 1; $i <= $pageCount; $i++) {
                    $tpl = $pdf->importPage($i);
                    $size = $pdf->getTemplateSize($tpl);
                    $pdf->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                    $pdf->useTemplate($tpl);
                }
            }

            // Return PDF as binary string (use correct Output signature)
            $pdfContent = $pdf->Output('', 'S');

            return response($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="preview.pdf"'
            ]);

        } catch (\Exception $e) {
            Log::error('PDF preview failed: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withErrors(['preview' => 'Failed to generate preview: '.$e->getMessage()]);
        } finally {
            foreach ($tempPaths as $p) {
                if (file_exists($p)) @unlink($p);
            }
        }
    }
}
