<?php

namespace App\Exceptions\ExceptionFormatters;

use App\Exceptions\ExceptionFormatter;
use Throwable;

abstract class TraceFormatter implements ExceptionFormatter
{
    private $traceLen = 5;

    public function __construct(int $traceLen)
    {
        $this->traceLen = $traceLen;
    }

    public function format(Throwable $exception) : array
    {
        $trace = $exception->getTrace();
        $trace = $this->slice($trace, $this->traceLen);
        $trace = array_map([$this, 'prettify'], $trace);
        return $trace;
    }

    // prettyPrint: compact multiline trace output.
    //   More compact than print_r.
    public function prettyPrint(
        Throwable $exception, int $indent = 0, string $indentChar = ' '
    ) : string
    {
        $trace = $this->format($exception);
        $trace = print_r($trace, true);
        $trace = explode(PHP_EOL, $trace);
        $trace = array_filter($trace, function (string $line) {
            $line = trim($line);
            return strlen($line) && $line != '(' && $line != ')';
        });
        $trace = array_map(function (string $line) {
            $trimmed = ltrim($line);
            $level = floor((strlen($line) - strlen($trimmed)) / 8);
            return str_repeat(' ', $level * 2).$trimmed;
        }, $trace);
        $shift = str_repeat($indentChar, $indent);
        return $shift.implode(PHP_EOL.$shift, $trace);
    }

    protected function slice(array $trace, int $len) : array
    {
        return array_slice($trace, 0, $len);
    }

    protected function prettify(array $trace) : array
    {
        // Remove /var/www from file:
        $file = str_replace(app()->basePath().'/', '', $trace['file'] ?? '');
        $trace['file'] = $file;
        // Remove useless keys:
        unset($trace['type']);
        return $trace;
    }
}
