<?php

namespace Stratum\Original\Presentation\Balance\Map;

Class HighPerformantMap
{
    protected $map;

    public function __construct($map)
    {
        $this->map = $map;
    }

    public function exists($key)
    {

        return $this->keyPosition($key) !== false;
    }

    public function get($key)
    {
        return substr($this->map, $this->endOfKeyPosition($key), $this->valueLength($key));
    }

    public function add(array $item)
    {
        $this->map.= "|({$item['key']}=>{$item['value']})|";
    }

    public function remove($key)
    {
        $this->map = substr_replace($this->map, '', $this->keyPosition($key), $this->keyAndValueLength($key));

        var_dump($this->map);
    }

    public function clear()
    {
        $this->map = '';
    }

    public function export()
    {
        return $this->map;
    }

    protected function keyPosition($key)
    {
        return strpos($this->map, $this->validKey($key));
    }

    protected function endOfKeyPosition($key)
    {
        (integer) $tokenLenght = 2;

        return $this->keyPosition($key) + strlen($this->validKey($key));
    }

    protected function keyAndValueLength($key)
    {
        (integer) $endOfItemPosition = strpos($this->map, ')|', $this->keyPosition($key));

        return ($endOfItemPosition - $this->keyPosition($key)) + 2;
    }

    protected function valueLength($key)
    {
        (integer) $endOfKeyPosition = $this->endOfKeyPosition($key);
        (integer) $endOfValuePosition = strpos($this->map, ')|', $endOfKeyPosition);
        return  $endOfValuePosition - $endOfKeyPosition;
    }

    protected function validKey($key)
    {
        return "|($key=>";
    }
}