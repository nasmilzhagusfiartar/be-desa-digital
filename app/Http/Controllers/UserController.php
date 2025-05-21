<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper; // Tambahkan jika ini helper kustom milikmu

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Ambil semua user (opsional search dan limit).
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string',
            'limit' => 'nullable|integer',
        ]);

        try {
            $users = $this->userRepository->getAll(
                $validated['search'] ?? null,
                $validated['limit'] ?? null,
                true
            );

            return ResponseHelper::jsonResponse(true, 'Data User Berhasil Diambil', UserResource::collection($users), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Ambil user dengan pagination.
     */
    public function getAllPaginated(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'nullable|integer',
        ]);

        try {
            $users = $this->userRepository->getAllPaginated(
                $validated['search'] ?? null,
                $validated['row_per_page'] ?? 10
            );

            return ResponseHelper::jsonResponse(true, 'Data User Berhasil Diambil', PaginateResource::make($users, UserResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    // Method CRUD lainnya — implementasi bisa kamu tambahkan sesuai kebutuhan

    public function store(UserStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $user = $this->userRepository->create($request);

            return ResponseHelper::jsonResponse(true, 'User berhasil ditambahkan', new UserResource($user), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function show(string $id)
    {
        try {
            $user = $this->userRepository->getById($id);

            if (!$user) {
                return ResponseHelper::jsonResponse(false, 'User tidak ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data User Berhasil Diambil', new UserResource($user), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function update(UserUpdateRequest $request, string $id)
    {
        $request = $request->validated();

        try {
            $user = $this->userRepository->getById($id);

            if (!$user) {
                return ResponseHelper::jsonResponse(false, 'User tidak ditemukan', null, 404);
            }

            $user = $this->userRepository->update($id, $request);

            

            return ResponseHelper::jsonResponse(true, 'Data User Berhasil Diupdate', new UserResource($user), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = $this->userRepository->getById($id);

            if (!$user) {
                return ResponseHelper::jsonResponse(false, 'User tidak ditemukan', null, 404);
            }

            $user = $this->userRepository->delete($id);

            

            return ResponseHelper::jsonResponse(true, 'Data User Berhasil Dihapus', new UserResource($user), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
