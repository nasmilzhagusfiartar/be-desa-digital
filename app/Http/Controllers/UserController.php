<?php

namespace App\Http\Controllers;

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

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
