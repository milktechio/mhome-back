<?php

namespace App\Models;

use App\Traits\Uuid;
use DB;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Token extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $fillable = ['expires_at', 'jti', 'revoked'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public static function decode($token)
    {
        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            return (array) $decoded;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function parse($data)
    {
        $user = User::find($data['sub']['id']) ?? false;
        if ($user) {
            return $user;
        }

        $user = new User;
        foreach ($data as $key => $value) {
            $user->$key = $value;
        }

        return $user;
    }

    public static function inlineDecode($token)
    {
        $tokenParts = explode('.', $token);

        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);

        $jwt = [];
        $jwt['header'] = json_decode($tokenHeader);
        $jwt['payload'] = json_decode($tokenPayload, true);

        return $jwt;
    }

    public static function check($token)
    {
        $jwt = Token::decode($token);
        if (! $jwt || ! isset($jwt['sub'])) {
            return false;
        }

        $valid = DB::table('tokens')->where('revoked', 0)->where('jti', $jwt['jti'])->first() ?? false;
        if ($valid) {
            return User::with('roles')->where('id', $jwt['sub']->id)->first();
        }

        return false;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function revoke()
    {
        $this->revoked = 1;
        $this->deleted_at = now();
        $this->save();
    }
}
