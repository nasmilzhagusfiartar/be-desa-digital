<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;

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
            $query->limit($limit); // Lebih eksplisit daripada take()
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
        $query = $this->getAll($search, $rowsPerPage, false);

        return $query->paginate($rowsPerPage ?? 10);
    }

    /**
     * Ambil user berdasarkan ID.
     */
    public function getById(string $id)
    {
        return User::find($id);
    }

    /**
     * Simpan user baru.
     */
    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);

            $user->save();

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception("Gagal menyimpan user: " . $e->getMessage());
        }
    }

    /**
     * Perbarui data user berdasarkan ID.
     */
    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);
            $user->name = $data['name'];

            if (isset($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->save();

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception("Gagal memperbarui user: " . $e->getMessage());
        }
    }

    /**
     * Hapus user berdasarkan ID.
     */
    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);
            $user->delete();

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception("Gagal menghapus user: " . $e->getMessage());
        }
    }
}
