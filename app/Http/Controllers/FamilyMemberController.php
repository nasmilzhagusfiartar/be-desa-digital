<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\FamilyMemberStoreRequest;
use App\Http\Requests\FamilyMemberUpdateRequest;
use App\Http\Resources\FamilyMemberResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\FamilyMemberRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class FamilyMemberController extends Controller
{
    private FamilyMemberRepositoryInterface $familyMemberRepository;

public function __construct(FamilyMemberRepositoryInterface $familyMemberRepository)
    {
        $this->familyMemberRepository = $familyMemberRepository;

        $this->middleware('permission:family-member-list|family-member-create|family-member-edit|family-member-delete', ['only' => ['index', 'getAllPaginated', 'show']]);
        $this->middleware('permission:family-member-create', ['only' => ['store']]);
        $this->middleware('permission:family-member-edit', ['only' => ['update']]);
        $this->middleware('permission:family-member-delete', ['only' => ['destroy']]);
    }

    /**
     * Menampilkan semua data anggota keluarga (opsional search & limit).
     */
    public function index(Request $request)
    {
        try {
            $familyMembers = $this->familyMemberRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Keluarga Berhasil Diambil',
                FamilyMemberResource::collection($familyMembers),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Menampilkan data anggota keluarga dengan pagination.
     */
    public function getAllPaginated(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer',
        ]);

        try {
            $familyMembers = $this->familyMemberRepository->getAllPaginated(
                $validated['search'] ?? null,
                $validated['row_per_page'],
                true
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Keluarga Berhasil Diambil',
                PaginateResource::make($familyMembers, FamilyMemberResource::class),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Simpan data anggota keluarga baru.
     */
    public function store(FamilyMemberStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $familyMember = $this->familyMemberRepository->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Data Anggota Keluarga Berhasil Ditambahkan',
                new FamilyMemberResource($familyMember),
                201
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Tampilkan detail anggota keluarga berdasarkan ID.
     */
    public function show(string $id)
    {
        try {
            $familyMember = $this->familyMemberRepository->getById($id);

            if (!$familyMember) {
                return ResponseHelper::jsonResponse(false, 'Data Anggota Keluarga Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(
                true,
                'Data Anggota Keluarga Berhasil Ditemukan',
                new FamilyMemberResource($familyMember),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Perbarui data anggota keluarga berdasarkan ID.
     */
    public function update(FamilyMemberUpdateRequest $request, string $id)
    {
        $request = $request->validated();

        try {
            $familyMember = $this->familyMemberRepository->getById($id);

            if (!$familyMember) {
                return ResponseHelper::jsonResponse(false, 'Data Anggota Keluarga Tidak Ditemukan', null, 404);
            }

            $familyMember = $this->familyMemberRepository->update($id, $request);

            return ResponseHelper::jsonResponse(
                true,
                'Data Anggota Keluarga Berhasil Diupdate',
                new FamilyMemberResource($familyMember),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Hapus data anggota keluarga berdasarkan ID.
     */
    public function destroy(string $id)
    {
        try {
            $familyMember = $this->familyMemberRepository->getById($id);

            if (!$familyMember) {
                return ResponseHelper::jsonResponse(false, 'Data Anggota Keluarga Tidak Ditemukan', null, 404);
            }

            $this->familyMemberRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Data Anggota Keluarga Berhasil Dihapus',
                null,
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
