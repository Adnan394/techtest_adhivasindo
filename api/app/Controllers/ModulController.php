<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ModulController extends BaseController
{
    public function index()
    {
        $modulModel = new \App\Models\Modul();
        $page     = (int) $this->request->getGet('page');
        $perPage  = (int) $this->request->getGet('per_page');

        $page    = $page > 0 ? $page : 1;
        $perPage = $perPage > 0 ? $perPage : 10;
        $search   = $this->request->getGet('search');

        // Filter search jika ada
        if ($search) {
            $modulModel->like('name', $search); // sesuaikan nama kolom
        }

        // Ambil data dengan pagination
        $data = $modulModel->paginate($perPage, 'default', $page);

        // Total data (untuk meta pagination)
        $total = $modulModel->countAllResults(false); // false supaya tidak reset query builder

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
        $modulModel = new \App\Models\Modul();
        $modul = $modulModel->find($id);

        return $this->response->setJSON([
            'code' => 200,
            'message' => 'success',
            'data' => $modul
        ]);
    }

    public function create()
    {
        // Ambil file upload
        $file = $this->request->getFile('image');
        $imagePath = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Pastikan folder tujuan ada
            $uploadPath = FCPATH . 'images/modul';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Bikin nama file unik
            $newName = $file->getRandomName();

            // Pindahkan file ke public/images/modul
            $file->move($uploadPath, $newName);

            // Simpan path relatif (biar gampang dipanggil di frontend)
            $imagePath = 'images/modul/' . $newName;
        }

        $data = [
            "id_kelas" => $this->request->getVar('id_kelas'),
            "name" => $this->request->getVar('name'),
            "description" => $this->request->getVar('description'),
            "image" => $imagePath,
            "created_at" => date('Y-m-d H:i:s'),
        ];

        $modulModel = new \App\Models\Modul();
        $modulModel->insert($data);

        return $this->response->setJSON([
            'code' => 200,
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function update($id)
    {
        $file = $this->request->getFile('image');
        $imagePath = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Pastikan folder tujuan ada
            $uploadPath = FCPATH . 'images/modul';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Bikin nama file unik
            $newName = $file->getRandomName();

            // Pindahkan file ke public/images/modul
            $file->move($uploadPath, $newName);

            // Simpan path relatif (biar gampang dipanggil di frontend)
            $imagePath = 'images/modul/' . $newName;
        }

        $data = [
            "id_kelas" => $this->request->getVar('id_kelas'),
            "name" => $this->request->getVar('name'),
            "description" => $this->request->getVar('description'),
            "image" => $imagePath,
            "updated_at" => date('Y-m-d H:i:s')
        ];

        $modulModel = new \App\Models\Modul();
        $modulModel->update($id, $data);

        return $this->response->setJSON([
            'code' => 200,
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function delete(int $id)
    {
        $modulModel = new \App\Models\Modul();
        $modulModel->delete($id);

        return $this->response->setJSON([
            'code' => 200,
            'message' => 'success'
        ], 200);
    }
}