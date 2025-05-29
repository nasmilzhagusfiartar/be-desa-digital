<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\HeadOfFamilyStoreRequest;
use App\Http\Requests\HeadOfFamilyUpdateRequest;
use App\Http\Resources\HeadOfFamilyResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\HeadOfFamilyRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use GuzzleHttp\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;


class HeadOfFamilyController extends Controller implements HasMiddleware
{
    private HeadOfFamilyRepositoryInterface $headOfFamilyRepository;

    public function __construct(HeadOfFamilyRepositoryInterface $headOfFamilyRepository)
    {
        $this->headOfFamilyRepository = $headOfFamilyRepository;
    }

    public static function middleware()
{
    return [
        'index' => [PermissionMiddleware::using([
            'head-of-family-list|head-of-family-create|head-of-family-edit|head-of-family-delete'
        ])],
        'getAllPaginated' => [PermissionMiddleware::using([
            'head-of-family-list|head-of-family-create|head-of-family-edit|head-of-family-delete'
        ])],
        'show' => [PermissionMiddleware::using([
            'head-of-family-list|head-of-family-create|head-of-family-edit|head-of-family-delete'
        ])],
        'store' => [PermissionMiddleware::using(['head-of-family-create'])],
        'update' => [PermissionMiddleware::using(['head-of-family-edit'])],
        'destroy' => [PermissionMiddleware::using(['head-of-family-delete'])],
    ];
}


    /**
     * Menampilkan semua data kepala keluarga (opsional search & limit).
     */
    public function index(Request $request)
    {
        try {
            $headOfFamilies = $this->headOfFamilyRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Kepala Keluarga Berhasil Diambil',
                HeadOfFamilyResource::collection($headOfFamilies),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Kepala Keluarga Gagal Diambil',
                null,
                500
            );
        }
    }

    /**
     * Menampilkan data kepala keluarga dengan pagination.
     */
    public function getAllPaginated(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'nullable|integer',
        ]);

        try {
            $headOfFamilies = $this->headOfFamilyRepository->getAllPaginated(
                $validated['search'] ?? null,
                $validated['row_per_page'] ?? 10
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Kepala Keluarga Berhasil Diambil',
                PaginateResource::make($headOfFamilies, HeadOfFamilyResource::class),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Kepala Keluarga Gagal Diambil',
                null,
                500
            );
        }
    }

    /**
     * Simpan data kepala keluarga baru.
     */
    public function store(HeadOfFamilyStoreRequest $request)
    {
        $validated = $request->validated();

        try {
            $headOfFamily = $this->headOfFamilyRepository->create($validated);

            return ResponseHelper::jsonResponse(
                true,
                'Kepala Keluarga berhasil ditambahkan',
                new HeadOfFamilyResource($headOfFamily),
                201
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Tampilkan detail kepala keluarga berdasarkan ID.
     */
    public function show(string $id)
    {
        try {
            $headOfFamily = $this->headOfFamilyRepository->getById($id);

            if (!$headOfFamily) {
                return ResponseHelper::jsonResponse(false, 'Kepala Keluarga tidak ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(
                true,
                'Detail Kepala Keluarga Berhasil Diambil',
                new HeadOfFamilyResource($headOfFamily),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Perbarui data kepala keluarga berdasarkan ID.
     */
    public function update(HeadOfFamilyUpdateRequest $request, string $id)
    {
        $validated = $request->validated();

        try {
            $headOfFamily = $this->headOfFamilyRepository->getById($id);

            if (!$headOfFamily) {
                return ResponseHelper::jsonResponse(false, 'Kepala Keluarga tidak ditemukan', null, 404);
            }

            $headOfFamily = $this->headOfFamilyRepository->update($id, $validated);

            return ResponseHelper::jsonResponse(
                true,
                'Kepala Keluarga Berhasil Diupdate',
                new HeadOfFamilyResource($headOfFamily),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Hapus data kepala keluarga berdasarkan ID.
     */
    public function destroy(string $id)
    {
        try {
            $headOfFamily = $this->headOfFamilyRepository->getById($id);

            if (!$headOfFamily) {
                return ResponseHelper::jsonResponse(false, 'Kepala Keluarga tidak ditemukan', null, 404);
            }

            $this->headOfFamilyRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Kepala Keluarga Berhasil Dihapus',
                null,
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
