<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Uuid
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
