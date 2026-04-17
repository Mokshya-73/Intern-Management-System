<?php

namespace App\Http\Controllers\Certificate;

use Illuminate\Http\Request;
use App\Models\InternProfile;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class CertificateControlle extends Controller
{
    public function download($reg_no)
    {
        $intern = InternProfile::where('reg_no', $reg_no)->firstOrFail();

        if (!$intern->certificate_generated_at) {
            return abort(403, 'Certificate not generated yet.');
        }

        $pdf = Pdf::loadView('certificates.template', ['intern' => $intern]);
        return $pdf->download("Certificate_{$intern->reg_no}.pdf");
    }
}
