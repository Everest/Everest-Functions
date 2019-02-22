<?php

/**
 * Get exception trace as a string without truncated text.
 *
 * @param Throwable $exception Exception to get trace from
 * @param bool      $nl2br     Set TRUE to convert line break to <br /> tags
 *
 * @return string
 */
function get_exception_trace(Throwable $exception, bool $nl2br = false): string
{
    $trace = '';
    $count = 0;
    foreach ($exception->getTrace() as $frame) {
        $args = '';
        if (isset($frame['args'])) {
            $args = [];
            foreach ($frame['args'] as $arg) {
                if (is_string($arg)) {
                    $args[] = '"'.$arg.'"';
                } elseif (is_array($arg)) {
                    $args[] = 'Array';
                } elseif (is_null($arg)) {
                    $args[] = 'NULL';
                } elseif (is_bool($arg)) {
                    $args[] = ($arg) ? 'true' : 'false';
                } elseif (is_object($arg)) {
                    $args[] = get_class($arg);
                } elseif (is_resource($arg)) {
                    $args[] = get_resource_type($arg);
                } else {
                    $args[] = $arg;
                }
            }
            $args = implode(', ', $args);
        }
        $frame['file'] = isset($frame['file']) ? $frame['file'] : '';
        $frame['line'] = isset($frame['line']) ? $frame['line'] : '';
        $frame['class'] = (isset($frame['class'])) ? $frame['class'].$frame['type'].$frame['function'] : $frame['function'];
        $trace .= sprintf('#%s %s(%s): %s(%s)', $count, $frame['file'], $frame['line'], $frame['class'], $args).($nl2br ? '<br />' : PHP_EOL);
        ++$count;
    }

    return $trace;
}
