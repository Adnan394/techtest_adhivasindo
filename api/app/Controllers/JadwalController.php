<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class JadwalController extends BaseController
{
    public function index()
    {
        $jadwalModel = new \App\Models\Jadwal();
        $page     = (int) $this->request->getGet('page');
        $perPage  = (int) $this->request->getGet('per_page');

        $page    = $page > 0 ? $page : 1;
        $perPage = $perPage > 0 ? $perPage : 10;
        $search   = $this->request->getGet('search');

        // Filter search jika ada
        if ($search) {
            $jadwalModel->like('name', $search); // sesuaikan nama kolom
        }

        // Ambil data dengan pagination
        $data = $jadwalModel->paginate($perPage, 'default', $page);

        // Total data (untuk meta pagination)
        $total = $jadwalModel->countAllResults(false); // false supaya tidak reset query builder

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
        $jadwalModel = new \App\Models\Jadwal();
        $jadwal = $jadwalModel->find($id);

        return $this->response->setJSON([
            'code' => 200,
            'message' => 'success',
            'data' => $jadwal
        ]);
    }

    public function create()
    {
        $data = [
            "id_user" => $this->request->getVar('id_user'),
            "flag_color" => $this->request->getVar('flag_color'),
            "tanggal" => $this->request->getVar('tanggal'),
            "name" => $this->request->getVar('name'),
            "description" => $this->request->getVar('description'),
            "created_at" => date('Y-m-d H:i:s'),
        ];

        $jadwalModel = new \App\Models\Jadwal();
        $jadwalModel->insert($data);

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
            "flag_color" => $this->request->getVar('flag_color'),
            "tanggal" => $this->request->getVar('tanggal'),
            "name" => $this->request->getVar('name'),
            "description" => $this->request->getVar('description'),
            "updated_at" => date('Y-m-d H:i:s')
        ];

        $jadwalModel = new \App\Models\Jadwal();
        $jadwalModel->update($id, $data);

        return $this->response->setJSON([
            'code' => 200,
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function delete(int $id)
    {
        $jadwalModel = new \App\Models\Jadwal();
        $jadwalModel->delete($id);

        return $this->response->setJSON([
            'code' => 200,
            'message' => 'success'
        ], 200);
    }
}