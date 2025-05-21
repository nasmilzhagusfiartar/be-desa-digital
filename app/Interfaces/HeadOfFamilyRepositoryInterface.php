<?php

namespace App\Interfaces;

interface HeadOfFamilyRepositoryInterface
{
    /**
     * Ambil semua data kepala keluarga, dengan opsi pencarian dan limitasi.
     *
     * @param string|null $search
     * @param int|null $limit
     * @param bool $execute
     * @return mixed
     */
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    );

    /**
     * Ambil data kepala keluarga dengan pagination.
     *
     * @param string|null $search
     * @param int|null $rowsPerPage
     * @return mixed
     */
    public function getAllPaginated(
        ?string $search,
        ?int $rowsPerPage
    );

    public function getById(
        string $id,
    );

    public function create(array $data);

    public function update(string $id, array $data);

    public function delete(string $id);
}
