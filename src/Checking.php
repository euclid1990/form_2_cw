<?php

namespace Src;

use Src\Google;
use Carbon\Carbon;
use Illuminate\Support\Collection;

define('SHEET', __DIR__ . '/../credentials/sheet_id.json');

class Checking {

    const SHEET_TAB_NAME = "";

    protected $spreadsheet;
    protected $spreadsheetId;
    protected $sheetTabName;
    protected $dt;
    protected $rangeRecords = "A2:C";
    protected $formDatetimeFormat = "d/m/Y H:i:s";
    protected $checkDatetimeFormat = "Y-m-d H:i";
    protected $ouputDatetimeFormat = "Y-m-d H:i:s";

    public function __construct() {
        $google = new Google();
        $this->spreadsheet = $google->getServiceSheets();
        $sheet = json_decode(file_get_contents(SHEET), true);
        $this->spreadsheetId = $sheet["id"];
        $this->dt = Carbon::now();
        $this->setSheetTabName();
    }

    public function setSheetTabName($name = "")
    {
        if (!empty($name)) {
            return $this->sheetTabName = $name;
        }
        return $this->sheetTabName = self::SHEET_TAB_NAME;
    }

    public function getRangeName($range)
    {
        return "{$this->sheetTabName}$range";
    }

    public function getValues($rangeType)
    {
        $range = $this->getRangeName($rangeType);
        $response = $this->spreadsheet->spreadsheets_values->get($this->spreadsheetId, $range);
        return $response->getValues();
    }

    public function getCurrentlyUpdate()
    {
        $values = $this->getValues($this->rangeRecords);
        if (empty($values)) {
            return new Collection([]);
        }
        $result = [];
        foreach ($values as $key => $row) {
            $current = $this->dt->copy()->subMinute()->format($this->checkDatetimeFormat);
            $createdAt = Carbon::createFromFormat($this->formDatetimeFormat, $row[0])->format($this->checkDatetimeFormat);
            if ($current === $createdAt) {
                $ouputDatetime = Carbon::createFromFormat($this->formDatetimeFormat, $row[0])->format($this->ouputDatetimeFormat);
                array_push($result, (object)[
                    'created_at' => $ouputDatetime,
                    'data' => json_encode(array_slice($row, 1), JSON_UNESCAPED_UNICODE),
                ]);
            }
        }
        unset($values);
        return new Collection($result);
    }

    public function exec()
    {
        $this->records = $this->getCurrentlyUpdate();
        $result = [];
        foreach ($this->records as $record) {
            $result[] = $record;
        }
        return $result;
    }

}