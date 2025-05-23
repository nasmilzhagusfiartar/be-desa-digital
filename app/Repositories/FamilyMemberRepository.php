<?php

namespace App\Repositories;

use App\Models\FamilyMember;
use App\Interfaces\FamilyMemberRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class FamilyMemberRepository implements FamilyMemberRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        $query = FamilyMember::with('user'); // eager load user

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($limit) {
            $query->limit($limit);
        }

        return $execute ? $query->get() : $query;
    }

    public function getAllPaginated(?string $search, ?int $rowsPerPage)
    {
        $query = $this->getAll($search, $rowsPerPage, false);
        return $query->paginate($rowsPerPage);
    }

    public function getById(string $id)
    {
        $query = FamilyMember::where('id', $id)->with('headOfFamily');
            
        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $userRepository = new UserRepository;

            $user = $userRepository->create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => $data['password'],
            ]);

            $familyMember = new FamilyMember;
            $familyMember->user_id          = $user->id;
            $familyMember->head_of_family_id = $data['head_of_family_id'];
            $familyMember->profile_picture  = $data['profile_picture']->store('assets/family-members', 'public');
            $familyMember->identity_number  = $data['identity_number'];
            $familyMember->gender           = $data['gender'];
            $familyMember->date_of_birth    = $data['date_of_birth'];
            $familyMember->phone_number     = $data['phone_number'];
            $familyMember->occupation       = $data['occupation'];
            $familyMember->marital_status   = $data['marital_status'];
            $familyMember->relation         = $data['relation'];
            $familyMember->save();

            DB::commit();

            return $familyMember;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $familyMember = FamilyMember::find($id);

            if (isset($data['profile_picture'])) {
                $familyMember->profile_picture = $data['profile_picture']->store('assets/family-members', 'public');
            }
            $familyMember->identity_number = $data['identity_number'];
            $familyMember->gender          = $data['gender'];
            $familyMember->date_of_birth   = $data['date_of_birth'];
            $familyMember->phone_number    = $data['phone_number'];
            $familyMember->occupation      = $data['occupation'];
            $familyMember->marital_status  = $data['marital_status'];
            $familyMember->relation         = $data['relation'];
            $familyMember->save();

            $userRepository = new UserRepository();

            $userRepository->update($familyMember->user_id, [
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => isset($data['password']) ? bcrypt($data['password']) : $familyMember->user->password,
            ]);

            DB::commit();

            return $familyMember;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $familyMember = FamilyMember::find($id);
            $familyMember->delete();

            DB::commit();

            return $familyMember;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }
}
