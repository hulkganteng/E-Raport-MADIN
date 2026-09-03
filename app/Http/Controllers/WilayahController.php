<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;

class WilayahController extends Controller
{
    /**
     * Get list of provinces.
     */
    public function provinces(): JsonResponse
    {
        $provinces = Province::query()
            ->orderBy('name')
            ->get(['code', 'name']);

        return response()->json($provinces);
    }

    /**
     * Get list of cities / regencies by province code or name.
     */
    public function cities(Request $request): JsonResponse
    {
        $code = $request->query('province_code');
        $name = $request->query('province_name');

        $query = City::query();

        if ($code) {
            $query->where('province_code', $code);
        } elseif ($name) {
            $province = Province::where('name', 'LIKE', $name)->first();
            if ($province) {
                $query->where('province_code', $province->code);
            } else {
                return response()->json([]);
            }
        } else {
            return response()->json([]);
        }

        $cities = $query->orderBy('name')->get(['code', 'name', 'province_code']);

        return response()->json($cities);
    }

    /**
     * Get list of districts by city code or name.
     */
    public function districts(Request $request): JsonResponse
    {
        $code = $request->query('city_code');
        $name = $request->query('city_name');

        $query = District::query();

        if ($code) {
            $query->where('city_code', $code);
        } elseif ($name) {
            $city = City::where('name', 'LIKE', $name)->first();
            if ($city) {
                $query->where('city_code', $city->code);
            } else {
                return response()->json([]);
            }
        } else {
            return response()->json([]);
        }

        $districts = $query->orderBy('name')->get(['code', 'name', 'city_code']);

        return response()->json($districts);
    }

    /**
     * Get list of villages by district code or name.
     */
    public function villages(Request $request): JsonResponse
    {
        $code = $request->query('district_code');
        $name = $request->query('district_name');

        $query = Village::query();

        if ($code) {
            $query->where('district_code', $code);
        } elseif ($name) {
            $district = District::where('name', 'LIKE', $name)->first();
            if ($district) {
                $query->where('district_code', $district->code);
            } else {
                return response()->json([]);
            }
        } else {
            return response()->json([]);
        }

        $villages = $query->orderBy('name')->get(['code', 'name', 'district_code', 'meta'])->map(function ($village) {
            $pos = null;
            if (is_array($village->meta) && isset($village->meta['pos'])) {
                $pos = $village->meta['pos'];
            }
            return [
                'code' => $village->code,
                'name' => $village->name,
                'district_code' => $village->district_code,
                'pos' => $pos,
            ];
        });

        return response()->json($villages);
    }
}

