<?php

namespace App\Repositories;

use App\Interfaces\SocialAssistanceRepositoryInterface;
use App\Models\SocialAssistance;
use Illuminate\Support\Facades\DB;
use Exception;

class SocialAssistanceRepository implements SocialAssistanceRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        $query = SocialAssistance::where(function($query) use ($search) {
            if ($search) {
            $query->search($search);
        }

        })->with('socialAssistanceRecipients');

        
        if ($limit) {
            $query->take($limit);
        }

        return $execute ? $query->get() : $query;
    }

    public function getAllPaginated(?string $search, ?int $rowPerPage)
    {
        $query = $this->getAll($search, $rowPerPage, false);
        return $query->paginate($rowPerPage);
    }

public function getById(string $id)
{
    return SocialAssistance::with('socialAssistanceRecipients')
        ->where('id', $id)
        ->first();
}

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $socialAssistance = new SocialAssistance;
            $socialAssistance->thumbnail    = $data['thumbnail']->store('assets/social-assistance', 'public');
            $socialAssistance->name         = $data['name'];
            $socialAssistance->category     = $data['category'];
            $socialAssistance->amount       = $data['amount'];
            $socialAssistance->provider     = $data['provider'];
            $socialAssistance->description  = $data['description'];
            $socialAssistance->is_available = $data['is_available'];
            $socialAssistance->save();

            DB::commit();

            return $socialAssistance;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $socialAssistance = SocialAssistance::find($id);

            if (isset($data['thumbnail'])) {
                $socialAssistance->thumbnail    = $data['thumbnail']->store('assets/social-assistance', 'public');
            }

            $socialAssistance->name         = $data['name'];
            $socialAssistance->category     = $data['category'];
            $socialAssistance->amount       = $data['amount'];
            $socialAssistance->provider     = $data['provider'];
            $socialAssistance->description  = $data['description'];
            $socialAssistance->is_available = $data['is_available'];
            $socialAssistance->save();

            DB::commit();

            return $socialAssistance;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
        
    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $socialAssistance = SocialAssistance::find($id);

            $socialAssistance->delete();

            DB::commit();

            return $socialAssistance;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
