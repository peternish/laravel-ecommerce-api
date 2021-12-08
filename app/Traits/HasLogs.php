<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait HasLogs
{
    public function save(array $options = [])
    {
        Log::channel('model')->debug('BEFORE: '.$this->toJson());
        $result = parent::save($options);
        Log::channel('model')->debug('AFTER: '.$this->toJson());
        return $result;
    }

    public function update(array $attributes = [], array $options = [])
    {
        Log::channel('model')->debug('BEFORE: '.$this->toJson());
        $result = parent::update($attributes, $options);
        Log::channel('model')->debug('AFTER: '.$this->toJson());
        return $result;
    }
}
