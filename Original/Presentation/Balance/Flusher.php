<?php

namespace Stratum\Original\Presentation\Balance;

Class Flusher
{
    public static function flush()
    {
        (boolean) $hasBuffers = ob_get_level() > 0;
        
        if ($hasBuffers) {
            ob_flush(); 
        }

        flush();
    }
}
