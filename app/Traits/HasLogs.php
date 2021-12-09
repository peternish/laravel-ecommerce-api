<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait HasLogs
{
    public function save(array $options = [])
    {
        $before = 'BEFORE: '.$this->toJson();
        $result = parent::save($options);
        Log::channel('model')->debug($before."\tAFTER: ".$this->toJson());
        return $result;
    }
}
