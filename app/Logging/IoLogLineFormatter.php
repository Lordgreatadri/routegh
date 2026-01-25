<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;

class IoLogLineFormatter extends LineFormatter
{
    const SIMPLE_FORMAT = "[%datetime%] [%BRIJ_HTTP_REQUEST_ID%]\n%message%\n\n";

    public function __construct()
    {
        return parent::__construct(null, 'Y-m-d\TH:i:s.uP', true);
    }

    /**
     * {@inheritdoc}
     */
    public function format(array $record): string
    {
        $record['BRIJ_HTTP_REQUEST_ID'] = BRIJ_REQUEST_ID;

        return parent::format($record);
    }
}
