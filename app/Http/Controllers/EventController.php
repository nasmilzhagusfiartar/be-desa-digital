<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\EventRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class EventController extends Controller implements HasMiddleware
{
    private EventRepositoryInterface $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public static function middleware()
{
    return [
        'index' => [PermissionMiddleware::using([
            'event-list|event-create|event-edit|event-delete'
        ])],
        'getAllPaginated' => [PermissionMiddleware::using([
            'event-list|event-create|event-edit|event-delete'
        ])],
        'show' => [PermissionMiddleware::using([
            'event-list|event-create|event-edit|event-delete'
        ])],
        'store' => [PermissionMiddleware::using(['event-create'])],
        'update' => [PermissionMiddleware::using(['event-edit'])],
        'destroy' => [PermissionMiddleware::using(['event-delete'])],
    ];
}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $events = $this->eventRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Event Berhasil Diambil',
                EventResource::collection($events),
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
            $events = $this->eventRepository->getAllPaginated(
                $request->input('search'),
                $request->input('row_per_page')
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Event Berhasil Diambil',
                PaginateResource::make($events, EventResource::class),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventStoreRequest $request)
    {
        $validated = $request->validated();

        try {
            $event = $this->eventRepository->create($validated);

            return ResponseHelper::jsonResponse(
                true,
                'Event Berhasil Dibuat',
                new EventResource($event),
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
            $event = $this->eventRepository->getById($id);

            if (!$event) {
                return ResponseHelper::jsonResponse(false, 'Event Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(
                true,
                'Event Berhasil Diambil',
                new EventResource($event),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventUpdateRequest $request, string $id)
    {
        $validated = $request->validated();

        try {
            $event = $this->eventRepository->getById($id);

            if (!$event) {
                return ResponseHelper::jsonResponse(false, 'Event Tidak Ditemukan', null, 404);
            }

            $event = $this->eventRepository->update($id, $validated);

            return ResponseHelper::jsonResponse(
                true,
                'Event Berhasil Diupdate',
                new EventResource($event),
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
            $event = $this->eventRepository->getById($id);

            if (!$event) {
                return ResponseHelper::jsonResponse(false, 'Event Tidak Ditemukan', null, 404);
            }

            $this->eventRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Event Berhasil Dihapus',
                null,
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
