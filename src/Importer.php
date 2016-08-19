<?php

namespace W;

class Importer
{
    protected $tree = null;
    protected $capsule = null;

    public function __construct(array $tree = null)
    {
        $this->setTree($tree);
    }

    public function setTree($tree) 
    {
        $this->tree = $tree;
    }

    public function setCapsule($capsule)
    {
        $this->capsule = $capsule;

        return $this;
    }

    public function import()
    {
        $this->importCountries();
        $this->importCountryDates();

        return $this;
    }

    protected function importCountries()
    {
        $this->capsule->table('countries')->truncate();
        $countriesData = [];
        foreach($this->tree as $shortcode => $country) {
            $countriesData[] = [
                'name' => $country['title'],
                'shortcode' => $shortcode,
            ];
        }

        $this->capsule->table('countries')->insert($countriesData);
    }

    protected function importCountryDates()
    {
        $this->capsule->table('country_date')->truncate();
        $countriesDates = [];

        /**
         * Assuming the auto increment didn't break
         */
        $countryInc = 1;
        foreach ($this->tree as $countryId => $countryData) {
            foreach ($countryData['months'] as $month => $dates) {
                foreach ($dates as $day) {
                    $countriesDates[] = [
                        'country_id' => $countryInc,
                        'date' => date('Y') . '-' . str_pad($month+1, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT),
                    ];
                }
            }
            $countryInc++;
        }

        $this->capsule->table('country_date')->insert($countriesDates);
    }

    public function updateCountryCoords()
    {
        foreach ($this->tree as $country) {
            $this->capsule->table('countries')
                ->where(['name' => $country['title']])
                ->update([
                    'lat' => $country['lat'],
                    'lon' => $country['lon'],
                ]);
        }

        return true;
    }

    public function calculateDistances()
    {
        $this->capsule->table('country_distance')->truncate();
        $distances = [];

        $countries = $this->capsule->table('countries')->get();

        foreach ($countries as $fromCountry) {
            foreach($countries as $toCountry) {
                $distances[] = [
                    'country_from_id' => $fromCountry->id,
                    'country_to_id' => $toCountry->id,
                    'distance' => Helper::getDistance($fromCountry->lat, $fromCountry->lon, $toCountry->lat, $toCountry->lon),
                ];
            }
        }

        $this->capsule->table('country_distance')->insert($distances);
    }
}