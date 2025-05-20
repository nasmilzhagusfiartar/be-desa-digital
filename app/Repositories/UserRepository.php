<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Ambil semua data user dengan optional search dan limit.
     */
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    ) {
        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($limit) {
            $query->take($limit);
        }

        return $execute ? $query->get() : $query;
    }

    /**
     * Ambil semua data user secara paginated.
     */
    public function getAllPaginated(
        ?string $search,
        ?int $rowsPerPage
    ) {
        $query = $this->getAll(
            $search,
            $rowsPerPage,
            false
        );

        return $query->paginate($rowsPerPage ?? 10);
    }
}
