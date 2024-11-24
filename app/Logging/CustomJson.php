<?php

namespace App\Logging;

use Monolog\Formatter\NormalizerFormatter;
use Monolog\LogRecord;

class CustomJson extends NormalizerFormatter
{
    private string $traceID;

    public function __construct($traceID = '')
    {
        parent::__construct('Y-m-d\TH:i:s.uP');

        $this->traceID = $traceID;
    }

    public function format(LogRecord $record): string
    {
        $recordData = parent::format($record);

        $message = [
            'datetime' => $recordData['datetime'],
        ];

        if (isset($this->traceID)) {
            $message['traceID'] = $this->traceID;
        }

        if (isset($recordData['level'])) {
            $message['level'] = $recordData['level'];
        }

        if (isset($recordData['message'])) {
            $message['message'] = $recordData['message'];
        }

        if (\count($recordData['context']) > 0) {
            $message['context'] = $recordData['context'];
        }

        if (\count($recordData['extra']) > 0) {
            $message['extra'] = $recordData['extra'];
        }
        $message['extra']['hostname'] = (string) gethostname();
        $message['extra']['app'] = config('app.name');
        $message['extra']['env'] = config('app.env');

        return $this->toJson($message)."\n";
    }
}
