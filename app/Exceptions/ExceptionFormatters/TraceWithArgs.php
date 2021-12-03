<?php

namespace App\Exceptions\ExceptionFormatters;

class TraceWithArgs extends TraceFormatter
{
    protected function prettify(array $trace) : array
    {
        $prettified = parent::prettify($trace);
        $prettified['args'] = $this->describe($trace['args'] ?? []);
        return $prettified;
    }

    private function describe(array $arr) : array
    {
        foreach ($arr as $key => $val) {
            if (is_resource($val)) {
                $type = get_resource_type($val);
                $meta = [ 'type' => $type ];
                if ($type == 'stream') {
                    $meta = array_merge($meta, stream_get_meta_data($val));
                }
                $val = [ 'resource' => $meta ];
            } elseif ($val instanceof \Closure) {
                $val = '{closure}';
            } elseif (is_object($val)) {
                $val = [ 'class' => get_class($val) ];
                if (method_exists($val['class'], '__toString')) {
                    $val['string representation'] = (string) $val['class'];
                }
            } elseif (is_array($val)) {
                $val = $this->describe($val);
            }
            $arr[$key] = $val;
        }
        return $arr;
    }
}
