<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penduduk;

class NikRecommendationController extends Controller
{
    /**
     * Return a light-weight NIK recommendation as JSON.
     * Query is small: we generate a small batch and check WHERE IN once.
     */
    public function recommend(Request $request)
    {
        $count = (int) $request->query('count', 5);
        $count = $count > 0 && $count <= 20 ? $count : 5;

        $attempts = 0;
        while ($attempts < 5) {
            $candidates = [];
            for ($i = 0; $i < $count; $i++) {
                $candidates[] = str_pad((string) random_int(0, 9999999999999999), 16, '0', STR_PAD_LEFT);
            }

            $exists = Penduduk::whereIn('nik', $candidates)->pluck('nik')->toArray();
            $free = array_values(array_diff($candidates, $exists));
            if (!empty($free)) {
                return response()->json(['nik' => $free[0]]);
            }
            $attempts++;
        }

        // fallback bounded loop
        for ($i = 0; $i < 50; $i++) {
            $cand = str_pad((string) random_int(0, 9999999999999999), 16, '0', STR_PAD_LEFT);
            if (!Penduduk::where('nik', $cand)->exists()) {
                return response()->json(['nik' => $cand]);
            }
        }

        return response()->json(['nik' => null], 500);
    }
}
