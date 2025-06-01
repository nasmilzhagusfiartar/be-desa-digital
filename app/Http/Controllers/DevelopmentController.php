<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\DevelopmentStoreRequest;
use App\Http\Requests\DevelopmentUpdateRequest;
use App\Http\Resources\DevelopmentResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\DevelopmentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class DevelopmentController extends Controller
{

    private DevelopmentRepositoryInterface $developmentRepository;

    public function __construct(DevelopmentRepositoryInterface $developmentRepository) {
        $this->developmentRepository = $developmentRepository;

         
        $this->middleware('permission:development-list|development-create|development-edit|development-delete', ['only' => ['index', 'getAllPaginated', 'show']]);
        $this->middleware('permission:development-create', ['only' => ['store']]);
        $this->middleware('permission:development-edit', ['only' => ['update']]);
        $this->middleware('permission:development-delete', ['only' => ['destroy']]);
    }

    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $development = $this->developmentRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Pembangunan Berhasil Diambil',
                DevelopmentResource::collection($development),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Display a paginated listing of the resource.
     */
    public function getAllPaginated(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer',
        ]);

        try {
            $development = $this->developmentRepository->getAllPaginated(
                $request->input('search'),
                $request->input('row_per_page')
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Pembangunan Berhasil Diambil',
                PaginateResource::make($development, DevelopmentResource::class),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DevelopmentStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $development = $this->developmentRepository->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Data Pembangunan Berhasil Dibuat',
                new DevelopmentResource($development),
                201
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
            $development = $this->developmentRepository->getById($id);

            if (!$development) {
                return ResponseHelper::jsonResponse(false, 'Data Pembangunan Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(
                true,
                'Data Pembangunan Berhasil Dibuat',
                new DevelopmentResource($development),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DevelopmentUpdateRequest $request, string $id)
    {
        try {
            $development = $this->developmentRepository->getById($id);

            if (!$development) {
                return ResponseHelper::jsonResponse(false, 'Data Pembangunan Tidak Ditemukan', null, 404);
            }

            $development = $this->developmentRepository->update($id, $request->validated());

            return ResponseHelper::jsonResponse(
                true,
                'Data Pembangunan Berhasil Diupdate',
                new DevelopmentResource($development),
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
            $development = $this->developmentRepository->getById($id);

            if (!$development) {
                return ResponseHelper::jsonResponse(false, 'Data Pembangunan Tidak Ditemukan', null, 404);
            }

            $this->developmentRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Data Pembangunan Berhasil Dihapus',
                null,
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
