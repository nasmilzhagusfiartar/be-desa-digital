<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\SocialAssistanceStoreRequest;
use App\Http\Requests\SocialAssistanceUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\SocialAssistanceResource;
use App\Interfaces\SocialAssistanceRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class SocialAssistanceController extends Controller
{
    private SocialAssistanceRepositoryInterface $socialAssistanceRepository;

    public function __construct(SocialAssistanceRepositoryInterface $socialAssistanceRepository)
    {
        $this->socialAssistanceRepository = $socialAssistanceRepository;

        
        $this->middleware('permission:social-assistance-list|social-assistance-create|social-assistance-edit|social-assistance-delete', ['only' => ['index', 'getAllPaginated', 'show']]);
        $this->middleware('permission:social-assistance-create', ['only' => ['store']]);
        $this->middleware('permission:social-assistance-edit', ['only' => ['update']]);
        $this->middleware('permission:social-assistance-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $socialAssistances = $this->socialAssistanceRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Bantuan Sosial Berhasil Diambil',
                SocialAssistanceResource::collection($socialAssistances),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Get paginated list of resources.
     */
    public function getAllPaginated(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer',
        ]);

        try {
            $socialAssistances = $this->socialAssistanceRepository->getAllPaginated(
                $validated['search'] ?? null,
                $validated['row_per_page']
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Bantuan Sosial Berhasil Diambil',
                PaginateResource::make($socialAssistances, SocialAssistanceResource::class),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SocialAssistanceStoreRequest $request)
    {
        $validated = $request->validated();

        try {
            $socialAssistance = $this->socialAssistanceRepository->create($validated);

            return ResponseHelper::jsonResponse(
                true,
                'Data Bantuan Sosial Berhasil Ditambahkan',
                new SocialAssistanceResource($socialAssistance),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $socialAssistance = $this->socialAssistanceRepository->getById($id);

            if (!$socialAssistance) {
                return ResponseHelper::jsonResponse(false, 'Data Bantuan Sosial Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(
                true,
                'Data Bantuan Sosial Berhasil Diambil',
                new SocialAssistanceResource($socialAssistance),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SocialAssistanceUpdateRequest $request, string $id)
    {
        $validated = $request->validated();

        try {
            $socialAssistance = $this->socialAssistanceRepository->getById($id);

            if (!$socialAssistance) {
                return ResponseHelper::jsonResponse(false, 'Data Bantuan Sosial Tidak Ditemukan', null, 404);
            }

            $updated = $this->socialAssistanceRepository->update($id, $validated);

            return ResponseHelper::jsonResponse(
                true,
                'Data Bantuan Sosial Berhasil Diperbarui',
                new SocialAssistanceResource($updated),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        try {
            $socialAssistance = $this->socialAssistanceRepository->getById($id);

            if (!$socialAssistance) {
                return ResponseHelper::jsonResponse(false, 'Data Bantuan Sosial Tidak Ditemukan', null, 404);
            }

            $socialAssistance = $this->socialAssistanceRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Data Bantuan Sosial Berhasil Dihapus',
                null,
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
