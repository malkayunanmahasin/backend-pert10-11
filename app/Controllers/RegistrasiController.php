<?php
namespace App\Controllers;
use App\Models\MRegistrasi;
class RegistrasiController extends RestfulController
{
    public function register()
    {
        $nama = $this->request->getVar('nama');
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        // Log percobaan registrasi (jangan log password)
        log_message('info', "Registrasi attempt: email={$email}, nama={$nama}");

        $data = [
            'nama'     => $nama,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ];

        $model = new MRegistrasi();
        if ($model->save($data)) {
            log_message('info', "Registrasi sukses: email={$email}, nama={$nama}");
            return $this->responseHasil(200, true, 'Registrasi berhasil');
        } else {
            $errors = $model->errors() ?? [];
            log_message('error', 'Registrasi gagal: email=' . $email . ' errors=' . json_encode($errors));
            return $this->responseHasil(500, false, 'Registrasi gagal');
        }
    }
}