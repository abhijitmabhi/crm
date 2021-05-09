<?php
namespace LocalheroPortal\Core\Traits;

use Illuminate\Support\Arr;

trait HasOptionsField
{
    public function set_option(string $key, $value) :void
    {
        $options = $this->options;
        Arr::set($options, $key, $value);
        $this->options = $options;
    }

    public function get_option(string $key = null, $default = null)
    {
        return Arr::get($this->options, $key, $default);
    }

    public function has_option($key) :bool
    {
        return Arr::has($this->options, $key);
    }

    public function getOptionsAttribute($value) 
    {
        return json_decode(!empty($value) ? $value : "{[]}" , true);
    }

    public function setOptionsAttribute($value) :void
    {
        $this->attributes['options'] = json_encode($value);
    }
}