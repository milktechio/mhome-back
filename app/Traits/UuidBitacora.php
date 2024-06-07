<?php

namespace App\Traits;

use App\Models\Bitacora;
use Auth;
use Illuminate\Support\Str;
use App\Models\User;

trait UuidBitacora
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            try {
                $model->id = (string) Str::uuid(); // generate uuid
                // Change id with your primary key
            } catch (\UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }

            if ($model->getTable() == 'bitacoras') {
                $model->user_id = Auth::user()->id ?? null;
                $model->database = env('DB_DATABASE');
            }

            return $model;
        });

        self::updating(function ($model) {
            $msg = [];
            if ($model->isDirty()) {
                foreach ($model->getAttributes() as $key => $campo) {
                    if ($model->isDirty($key)) {
                        $value = $model->getOriginal($key);
                        $user = $model->getOriginal('username');

                        $value = is_array($value) ? json_encode($value) : $value;
                        $campo = is_array($campo) ? json_encode($campo) : $campo;

                        $username = Auth::user()->username ?? '???';

                        $msg[] = "El usuario '".$username."' Ha modificado el campo '$key' del usuario '$user' de: '$value' a '$campo'";
                    }
                }
                Bitacora::create([
                    'msg' => $msg,
                    'table' => $model->getTable(),
                    'table_id' => $model->id,
                ]);
            }
        });

        self::deleting(function ($model) {
            $user = $model->getOriginal('username');
            $userAuth = Auth::user()->username ?? '???';
            $msg = [];

            if ($user == $userAuth) {
                $msg[] = "El usuario '$userAuth' Ha eliminado su cuenta";
            } else {
                $msg[] = "El usuario '$userAuth' Ha eliminado la cuenta de: '$user'";
            }

            Bitacora::create([
                'msg' => $msg,
                'table' => app(User::class)->getTable(),
                'table_id' => $model->id,
            ]);
        });
    }

    public function getFresh()
    {
        $db = ($this::class)::whereNull('deleted_at');

        if (isset($this->fillable)) {
            foreach ($this->fillable as $key) {
                $db->where($key, $this->$key);
            }
        }
        if (isset($this->hidden)) {
            foreach ($this->hidden as $key) {
                $db->where($key, $this->$key);
            }
        }

        return $db->first();
    }
}
