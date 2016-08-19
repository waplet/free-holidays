<?php

namespace W;

class CsvParser
{
    protected $data = [];
    protected $tree = [];

    public function __construct($fileName)
    {
        if (!file_exists(realpath($fileName))) {
            throw new \ErrorException("Error while getting file");    
        }

        $data = file($fileName);
        foreach ($data as $row) {
            $this->data[] = str_getcsv($row, ';', '');
        }
    }

    public function parseIntoTree(): CsvParser
    {
        foreach ($this->data as $countryData) {
            $key = Helper::trim($countryData[0]);
            $this->tree[$key] = [
                'title' => $countryData[1],
            ];

            $months = [];
            for ($i = 2; $i < 14; $i++) {
                $dates = Helper::parseDates($countryData[$i]);
                if ($dates) {
                    $months[$i-2] = $dates;
                }
            }

            $this->tree[$key]['months'] = $months;
        }

        return $this;
    }

    public function parseIntoLatLons(): CsvParser
    {
        foreach ($this->data as $id => $countryData) {
            $this->tree[] = [
                'title' => $countryData[0],
                'lat' => $countryData[1],
                'lon' => $countryData[2],
            ];
        }

        return $this;
    }

    public function getTree(): array
    {
        return $this->tree;
    }
}