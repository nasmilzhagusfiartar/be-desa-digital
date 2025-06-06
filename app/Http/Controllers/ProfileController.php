<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ProfileStoreRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\ProfileResource;
use App\Interfaces\ProfileRepositoryInterface;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private ProfileRepositoryInterface $profileRepository;

    public function __construct(ProfileRepositoryInterface $profileRepository) {
        $this->profileRepository = $profileRepository;
    }

    public function index() {
        try {
            $profile = $this->profileRepository->get();

            if (!$profile) {
                return ResponseHelper::jsonResponse(false, 'Profile Tidak Ditemukan', null, 404);
            }
            
            return ResponseHelper::jsonResponse(true, 'Profile Berhasil Diambil', new ProfileResource($profile), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function store(ProfileUpdateRequest $request)
    {
        $request = $request->validated();

        try {
            $profile = $this->profileRepository->create($request);

            return ResponseHelper::jsonResponse(true, 'Profile Berhasil Dibuat', new ProfileResource($profile), 201);
         
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function update(ProfileUpdateRequest $request)
    {
        $request = $request->validated();

        try {
            $profile = $this->profileRepository->update($request);

            return ResponseHelper::jsonResponse(true, 'Profile Berhasil Diubah', new ProfileResource($profile), 200);
         
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
