<?php

namespace Stratum\Original\HTTP\Cleaner;

Class BufferCleaner
{
    protected static $savedBuffer;

    public function storeTemporarily()
    {
        Static::$savedBuffer = ob_get_contents();
    }

    public function cleanBuffersIfFull()
    {
        if ($this->thereAreBuffersToClean() and $this->bufferHasContent()) {
            ob_end_clean();
        }
    }

    protected function bufferHasContent()
    {
        return !empty(ob_get_contents());
    }

    public function restoreBuffers()
    {
        ob_start();
            print Static::$savedBuffer;

    }

    protected function thereAreBuffersToClean()
    {
        return ob_get_status();
    }
}