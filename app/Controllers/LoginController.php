<?php 
namespace App\Controllers;
use App\Models\Mlogin;
use App\Controllers\MMember;
use App\Models\MMember as ModelsMMember;

class LoginController extends RestfulController
{
    public function login()
    {
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $member= new ModelsMMember();
        $member = $member->where('email', $email)->first();

        if (!$member) {
            return $this->responseHasil(400, false, 'email tidak ditemukan');
        }

        if (!password_verify($password, $member['password'])) {
            return $this->responseHasil(400, false, 'Password tidak valid');
        }

        // Generate auth key (token)

        // Save token to database
        $login= new Mlogin();
        $auth_key= $this->RandomString();
        $login->save([
            'member_id' => $member['id'],
            'auth_key'  => $auth_key,
        ]);

        $data = [
            'auth_key' => $auth_key,
            'user'   => [
                'id'    => $member['id'],
                'email' => $member['email'],
            ],
        ];
        return $this->responseHasil(200, true, $data);
    }
    
    private function RandomString($length = 100)
    {
        $karakter = '012345678dssd9abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $panjang_karakter = strlen($karakter);
    $str = '';

        for ($i = 0; $i < $length; $i++) {
        $str .= $karakter[rand(0, $panjang_karakter - 1)];
    }
    return $str;
}
}
