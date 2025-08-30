<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\jwt_helpers;
use App\Controllers\BaseController;

class AuthController extends BaseController
{
    protected $format = 'json';
    public function login()
    {
        $userModel = new User();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $user = $userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return $this->response->setJSON(['message' => 'Email atau password salah']);
        }

        $token = createJWT($user);

        return $this->response->setJSON(['token' => $token]);
    }

    public function register()
    {
        $userModel = new User();

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

        // Simpan ke database
        $data = [
            'name'     => $this->request->getVar('name'),
            'email'    => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'role'     => 'user',
            'image'    => $imagePath, // simpan path image
        ];

        $userModel->insert($data);

        return $this->response->setJSON([
            'message' => 'User registered',
            'user'    => $data,
        ]);
    }

}