<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class NilaiController extends BaseController
{
    public function index()
    {
        $NilaiModel = new \App\Models\Nilai();
        $page     = (int) $this->request->getGet('page');
        $perPage  = (int) $this->request->getGet('per_page');

        $page    = $page > 0 ? $page : 1;
        $perPage = $perPage > 0 ? $perPage : 10;
        $search   = $this->request->getGet('search');

        $builder = $NilaiModel
            ->select('nilai.*, users.name as user_name, kelas.name as kelas_name, moduls.name as modul_name')
            ->join('kelas', 'kelas.id = nilai.id_kelas', 'left')
            ->join('moduls', 'moduls.id = nilai.id_modul', 'left')
            ->join('users', 'users.id = nilai.id_user', 'left');

        if ($search) {
            $builder->like('users.name', $search) // contoh: search di nama user
                    ->orLike('kelas.name', $search)
                    ->orLike('moduls.name', $search);
        }

        // paginate
        $data = $builder->paginate($perPage, 'default', $page);
        $total = $builder->countAllResults(false);

        return $this->response->setJSON([
            'code' => 200,
            'message' => 'success',
            'data' => $data,
            'meta' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_page' => ceil($total / $perPage)
            ]
        ]);
    }


    public function show(int $id): ResponseInterface
    {
        $NilaiModel = new \App\Models\Nilai();
        $nilai = $NilaiModel->find($id);

        return $this->response->setJSON([
            'code' => 200,
            'message' => 'success',
            'data' => $nilai
        ]);
    }

    public function create()
    {
        $data = [
            "id_user" => $this->request->getVar('id_user'),
            "id_kelas" => $this->request->getVar('id_kelas'),
            "id_modul" => $this->request->getVar('id_modul'),
            "point" => $this->request->getVar('point'),
            "created_at" => date('Y-m-d H:i:s'),
        ];

        $NilaiModel = new \App\Models\Nilai();
        $NilaiModel->insert($data);

        return $this->response->setJSON([
            'code' => 200,
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function update($id)
    {
        $data = [
            "id_user" => $this->request->getVar('id_user'),
            "id_kelas" => $this->request->getVar('id_kelas'),
            "id_modul" => $this->request->getVar('id_modul'),
            "point" => $this->request->getVar('point'),
            "updated_at" => date('Y-m-d H:i:s')
        ];

        $NilaiModel = new \App\Models\Nilai();
        $NilaiModel->update($id, $data);

        return $this->response->setJSON([
            'code' => 200,
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function delete(int $id)
    {
        $NilaiModel = new \App\Models\Nilai();
        $NilaiModel->delete($id);

        return $this->response->setJSON([
            'code' => 200,
            'message' => 'success'
        ], 200);
    }
}