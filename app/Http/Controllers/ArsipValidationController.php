<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArsipValidationController extends Controller
{
    /**
     * Cek nomor arsip aktif
     */
    public function checkNomorArsipAktif(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor' => 'required|string',
            'id' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor arsip wajib diisi'
            ], 400);
        }

        $nomor = $request->nomor;
        $id = $request->id; // ID saat edit

        // Query untuk mengecek nomor arsip
        $query = "SELECT COUNT(*) as count FROM arsip_aktif WHERE nomor_arsip = ?";
        
        // Jika sedang edit, exclude ID saat ini dari pengecekan
        if (!empty($id)) {
            $query .= " AND id != ?";
            $result = DB::select($query, [$nomor, $id]);
        } else {
            $result = DB::select($query, [$nomor]);
        }

        $exists = $result[0]->count > 0;

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Nomor arsip sudah digunakan' : 'Nomor arsip tersedia'
        ]);
    }

    /**
     * Cek nomor arsip inaktif
     */
    public function checkNomorArsipInaktif(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor' => 'required|string',
            'id' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor arsip wajib diisi'
            ], 400);
        }

        $nomor = $request->nomor;
        $id = $request->id; // ID saat edit

        // Query untuk mengecek nomor arsip
        $query = "SELECT COUNT(*) as count FROM arsip_inaktif WHERE nomor_arsip = ?";
        
        // Jika sedang edit, exclude ID saat ini dari pengecekan
        if (!empty($id)) {
            $query .= " AND id != ?";
            $result = DB::select($query, [$nomor, $id]);
        } else {
            $result = DB::select($query, [$nomor]);
        }

        $exists = $result[0]->count > 0;

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Nomor arsip sudah digunakan' : 'Nomor arsip tersedia'
        ]);
    }

    /**
     * Cek nomor arsip vital
     */
    public function checkNomorArsipVital(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor' => 'required|string',
            'id' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor arsip wajib diisi'
            ], 400);
        }

        $nomor = $request->nomor;
        $id = $request->id; // ID saat edit

        // Query untuk mengecek nomor arsip
        $query = "SELECT COUNT(*) as count FROM arsip_vital WHERE nomor_arsip = ?";
        
        // Jika sedang edit, exclude ID saat ini dari pengecekan
        if (!empty($id)) {
            $query .= " AND id != ?";
            $result = DB::select($query, [$nomor, $id]);
        } else {
            $result = DB::select($query, [$nomor]);
        }

        $exists = $result[0]->count > 0;

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Nomor arsip sudah digunakan' : 'Nomor arsip tersedia'
        ]);
    }

    /**
     * Cek nomor arsip alih media
     */
    public function checkNomorArsipAlihmedia(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor' => 'required|string',
            'id' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor arsip wajib diisi'
            ], 400);
        }

        $nomor = $request->nomor;
        $id = $request->id; // ID saat edit

        // Query untuk mengecek nomor arsip
        $query = "SELECT COUNT(*) as count FROM arsip_alihmedia WHERE nomor_arsip = ?";
        
        // Jika sedang edit, exclude ID saat ini dari pengecekan
        if (!empty($id)) {
            $query .= " AND id != ?";
            $result = DB::select($query, [$nomor, $id]);
        } else {
            $result = DB::select($query, [$nomor]);
        }

        $exists = $result[0]->count > 0;

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Nomor arsip sudah digunakan' : 'Nomor arsip tersedia'
        ]);
    }

    /**
     * Cek nomor arsip universal (bisa digunakan untuk semua jenis)
     */
    public function checkNomorArsipUniversal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor' => 'required|string',
            'jenis' => 'required|in:aktif,inaktif,vital,alihmedia',
            'id' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], 400);
        }

        $nomor = $request->nomor;
        $jenis = $request->jenis;
        $id = $request->id;

        // Tentukan tabel berdasarkan jenis
        $tableMap = [
            'aktif' => 'arsip_aktif',
            'inaktif' => 'arsip_inaktif',
            'vital' => 'arsip_vital',
            'alihmedia' => 'arsip_alihmedia'
        ];

        $tableName = $tableMap[$jenis] ?? null;
        if (!$tableName) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis arsip tidak valid'
            ], 400);
        }

        // Query untuk mengecek nomor arsip
        $query = "SELECT COUNT(*) as count FROM {$tableName} WHERE nomor_arsip = ?";
        
        // Jika sedang edit, exclude ID saat ini dari pengecekan
        if (!empty($id)) {
            $query .= " AND id != ?";
            $result = DB::select($query, [$nomor, $id]);
        } else {
            $result = DB::select($query, [$nomor]);
        }

        $exists = $result[0]->count > 0;

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Nomor arsip sudah digunakan' : 'Nomor arsip tersedia',
            'jenis' => $jenis
        ]);
    }
}