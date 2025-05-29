<?php

namespace App\Repositories;

use App\Interfaces\ProfileRepositoryInterface;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Exception;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function get()
    {
        return Profile::first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $profile = new Profile();
            $profile->thumbnail = $data['thumbnail']->store('assets/profiles', 'public');
            $profile->name = $data['name'];
            $profile->about = $data['about'];
            $profile->headman = $data['headman'];
            $profile->people = $data['people'];
            $profile->agriculture_area = $data['agriculture_area'];
            $profile->total_area = $data['total_area'];
            $profile->save();

            if (array_key_exists('images', $data)) {
                foreach ($data['images'] as $image) {
                    $profile->profileImages()->create([
                        'image' => $image->store('assets/profiles', 'public')
                    ]);
                }
            }

            DB::commit();
            return $profile;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(array $data)
    {
        DB::beginTransaction();

        try {
            $profile = Profile::first();

            if (isset($data['thumbnail'])) {
                $profile->thumbnail = $data['thumbnail']->store('assets/profiles', 'public');
            }

            $profile->name = $data['name'];
            $profile->about = $data['about'];
            $profile->headman = $data['headman'];
            $profile->people = $data['people'];
            $profile->agriculture_area = $data['agriculture_area'];
            $profile->total_area = $data['total_area'];
            $profile->save();

            if (array_key_exists('images', $data)) {
                foreach ($data['images'] as $image) {
                    $profile->profileImages()->create([
                        'image' => $image->store('assets/profiles', 'public')
                    ]);
                }
            }

            DB::commit();
            return $profile;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
