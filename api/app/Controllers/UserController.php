<?php

namespace App\Controllers;

use App\Models\User;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
{
    public function index()
    {
        $user = new User();
        $data = $user->where('id', $this->request->user->uid)->first();
        
        return $this->response->setJSON([
            'code' => 200,
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function update() {
        // Ambil file upload
        $file = $this->request->getFile('image');
        $imagePath = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Pastikan folder tujuan ada
            $uploadPath = FCPATH . 'images/user';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Bikin nama file unik
            $newName = $file->getRandomName();

            // Pindahkan file ke public/images/user
            $file->move($uploadPath, $newName);

            // Simpan path relatif (biar gampang dipanggil di frontend)
            $imagePath = 'images/user/' . $newName;
        }
        
        $user = new User();
        $data = [
            'name' => $this->request->getVar('name'),
            'email' => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if($imagePath != null) {
            $data['image'] = $imagePath;
        }
        $user->update($this->request->user->uid, $data);

        return $this->response->setJSON([
            'code' => 200,
            'message' => 'success',
            'data' => $data
        ], 200);
    }
}