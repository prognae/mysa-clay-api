<?php

namespace App\Console\Commands;

use App\Models\Barangay;
use App\Models\District;
use App\Models\Region;
use App\Models\Province;
use App\Models\IslandGroup;
use App\Models\Municipality;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PopulateDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:geoloc-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->setIslandGroupData();
        $this->setRegionData();
        $this->setProvinceData();
        $this->setDistrictData();
        $this->setMunicipalityData();
        $this->info('POPULATION OF PH GEOLOCATION DONE!!');
    }

    public function setIslandGroupData() {
        $response = Http::get('https://psgc.gitlab.io/api/island-groups/');

        foreach($response->json() as $location) {
            IslandGroup::create([
                'code' => $location['code'],
                'name' => $location['name']
            ]);
        }

        $this->info('POPULATING ISLAND GROUP DATA DONE!');
    }

    public function setRegionData() {
        $islandGroups = IslandGroup::all();

        foreach($islandGroups as $island) {
            $response = Http::get('https://psgc.gitlab.io/api/island-groups/' . $island->code . '/regions/');

            foreach($response->json() as $location) {
                Region::create([
                    'code' => $location['code'],
                    'name' => $location['name'],
                    'region_name' => $location['regionName'],
                    'island_group_code' => $location['islandGroupCode']
                ]);
            }
        }

        $this->info('POPULATING REGION DATA DONE!');
    }

    public function setProvinceData() {
        $islandGroups = IslandGroup::all();

        foreach($islandGroups as $island) {
            sleep(2);
            $response = Http::get('https://psgc.gitlab.io/api/island-groups/' . $island->code . '/provinces/');

            foreach($response->json() as $location) {
                Province::create([
                    'code' => $location['code'],
                    'name' => $location['name'],
                    'region_code' => $location['regionCode'],
                    // 'area_code' => $this->getAreaCode($location['name']), 
                    'island_group_code' => $location['islandGroupCode']
                ]);
            }
        }

        $this->info('POPULATING PROVINCE DATA DONE!');
    }

    public function setDistrictData() {
        $islandGroups = IslandGroup::all();

        foreach($islandGroups as $island) {
            sleep(5);
            $response = Http::get('https://psgc.gitlab.io/api/island-groups/' . $island->code . '/districts/');

            foreach($response->json() as $location) {
                District::create([
                    'code' => $location['code'],
                    'name' => $location['name'],
                    'region_code' => $location['regionCode'],
                    'island_group_code' => $location['islandGroupCode']
                ]);
            }
        }

        $this->info('POPULATING DISTRICT DATA DONE!');
    }

    public function setMunicipalityData() {
        $islandGroups = IslandGroup::all();

        foreach($islandGroups as $island) {
            sleep(5);
            $response = Http::get('https://psgc.gitlab.io/api/island-groups/' . $island->code . '/cities-municipalities/');

            foreach($response->json() as $location) {
                Municipality::create([
                    'code' => $location['code'],
                    'name' => $location['name'],
                    'old_name' => $location['oldName'],
                    'is_capital' => $location['isCapital'],
                    'district_code' => $location['districtCode'],
                    'province_code' => $location['provinceCode'],
                    'region_code' => $location['regionCode'],
                    // 'area_code' => $this->getMunicipalCode($location['name']),
                    'island_group_code' => $location['islandGroupCode']
                ]);
            }
        }

        $this->info('POPULATING MUNICIPALITY DATA DONE!');
    }

    public function setBarangayData() {
        $islandGroups = IslandGroup::all();

        foreach($islandGroups as $island) {
            sleep(5);
            $response = Http::get('https://psgc.gitlab.io/api/island-groups/' . $island->code . '/barangays/');

            foreach($response->json() as $location) {
                Barangay::create([
                    'code' => $location['code'],
                    'name' => $location['name'],
                    'old_name' => $location['oldName'],
                    'sub_municipality_code' => $location['subMunicipalityCode'],
                    'municipality_code' => $location['municipalityCode'],
                    'province_code' => $location['provinceCode'],
                    'region_code' => $location['regionCode'],
                    'city_code' => $location['cityCode'],
                    'district_code' => $location['districtCode'],
                    'island_group_code' => $location['islandGroupCode']
                ]);
            }
        }

        $this->info('POPULATING MUNICIPALITY DATA DONE!');
    }

    public function getAreaCode($province) {
        if($province == 'Abra') {
            return 'CAR_ABR';
        }

        if($province == 'Aklan') {
            return 'R6_AKL';
        }

        if($province == 'Zamboanga del Norte') {
            return 'R9_ZDN';
        }

        if($province == 'Apayao') {
            return 'CAR_APY';
        }

        if($province == 'Antique') {
            return 'R6_ANT';
        }

        if($province == 'Zamboanga del Sur') {
            return 'R9_ZDS';
        }

        if($province == 'Benguet') {
            return 'CAR_BNG';
        }

        if($province == 'Capiz') {
            return 'R6_CPZ';
        }

        if($province == 'Zamboanga Sibugay') {
            return 'R9_ZBS';
        }

        if($province == 'Ifugao') {
            return 'CAR_IFG';
        }

        if($province == 'Guimaras') {
            return 'R6_GMR';
        }

        if($province == 'Bukidnon') {
            return 'RX_BKD';
        }

        if($province == 'Kalinga') {
            return 'CAR_KLN';
        }

        if($province == 'Iloilo') {
            return 'R6_ILO';
        }

        if($province == 'Camiguin') {
            return 'RX_CMG';
        }

        if($province == 'Mountain Province') {
            return 'CAR_MTP';
        }

        if($province == 'Negros Occidental') {
            return 'R6_NGO';
        }

        if($province == 'Lanao del Norte') {
            return 'RX_LDN';
        }

        if($province == 'Ilocos Norte') {
            return 'RI_ILN';
        }

        if($province == 'Bohol') {
            return 'R7_BHL';
        }

        if($province == 'Misamis Occidental') {
            return 'RX_MOC';
        }

        if($province == 'Ilocos Sur') {
            return 'RI_ILS';
        }

        if($province == 'Cebu') {
            return 'R7_CEB';
        }

        if($province == 'Misamis Oriental') {
            return 'RX_MOR';
        }

        if($province == 'La Union') {
            return 'RI_LU';
        }

        if($province == 'Negros Oriental') {
            return 'R7_NGO';
        }

        if($province == 'Davao del Norte') {
            return 'RXI_DDN';
        }

        if($province == 'Pangasinan') {
            return 'RI_PNG';
        }

        if($province == 'Siquijor') {
            return 'R7_SQJ';
        }

        if($province == 'Davao del Sur') {
            return 'RXI_DDS';
        }

        if($province == 'Batanes') {
            return 'R2_BTN';
        }

        if($province == 'Biliran') {
            return 'R8_BLR';
        }

        if($province == 'Davao Oriental') {
            return 'RXI_DVO';
        }

        if($province == 'Cagayan') {
            return 'R2_CGY';
        }

        if($province == 'Eastern Samar') {
            return 'R8_ETS';
        }

        if($province == 'Davao de Oro') {
            return 'RXI_CTB';
        }

        if($province == 'Isabela') {
            return 'R2_ISB';
        }

        if($province == 'Leyte') {
            return 'R8_LYT';
        }

        if($province == 'Sarangani') {
            return 'RXII_SRG';
        }

        if($province == 'Nueva Vizcaya') {
            return 'R2_NVZ';
        }

        if($province == 'Northern Samar') {
            return 'R8_NRS';
        }

        if($province == 'South Cotabato') {
            return 'RXII_SCT';
        }

        if($province == 'Quirino') {
            return 'R2_QRN';
        }

        if($province == 'Samar') {
            return 'R8_SMR';
        }

        if($province == 'Sultan Kudarat') {
            return 'RXII_STK';
        }

        if($province == 'Aurora') {
            return 'R3_ARR';
        }

        if($province == 'Southern Leyte') {
            return 'R8_ATL';
        }

        if($province == 'Cotabato') {
            return 'RXII_CTB';
        }

        if($province == 'Bataan') {
            return 'R3_BTN';
        }

        if($province == 'Agusan del Norte') {
            return 'R13_ADN';
        }

        if($province == 'Bulacan') {
            return 'R3_BLC';
        }

        if($province == 'Agusan del Sur') {
            return 'R13_ADS';
        }

        if($province == 'Nueva Ecija') {
            return 'R3_NVE';
        }

        if($province == 'Dinagat Islands') {
            return 'R13_DGI';
        }

        if($province == 'Pampanga') {
            return 'R3_PMP';
        }

        if($province == 'Surigao del Norte') {
            return 'R13_SDN';
        }

        if($province == 'Tarlac') {
            return 'R3_TLC';
        }

        if($province == 'Surigao del Sur') {
            return 'R13_SDS';
        }

        if($province == 'Zambales') {
            return 'R3_ZMB';
        }

        if($province == 'Lanao del Sur') {
            return 'BAR_LDS';
        }

        if($province == 'Batangas') {
            return 'R4_BTG';
        }

        if($province == 'Maguindanao') {
            return 'BAR_MGN';
        }

        if($province == 'Cavite') {
            return 'R4_CVT';
        }

        if($province == 'Sulu') {
            return 'BAR_SL';
        }

        if($province == 'Laguna') {
            return 'R4_LGN';
        }

        if($province == 'Tawi-Tawi') {
            return 'BAR_TWT';
        }

        if($province == 'Quezon Province') {
            return 'R4_QZP';
        }

        if($province == 'Basilan') {
            return 'BAR_BSL';
        }

        if($province == 'Rizal') {
            return 'R4_RZL';
        }

        if($province == 'Marinduque') {
            return 'R4B_MRN';
        }

        if($province == 'Occidental Mindoro') {
            return 'R4B_OCM';
        }

        if($province == 'Oriental Mindoro') {
            return 'R4B_ORM';
        }

        if($province == 'Palawan') {
            return 'R4B_PLW';
        }

        if($province == 'Romblon') {
            return 'R4B_RMB';
        }

        if($province == 'Albay') {
            return 'R5_ALB';
        }

        if($province == 'Camarines Norte') {
            return 'R5_CMN';
        }

        if($province == 'Camarines Sur') {
            return 'R5_CMS';
        }

        if($province == 'Catanduanes') {
            return 'R5_CTN';
        }

        if($province == 'Masbate') {
            return 'R5_MSB';
        }

        if($province == 'Sorsogon') {
            return 'R5_SRS';
        }
    }

    public function getMunicipalCode($municipality) {
        if($municipality == 'City of Caloocan') {
            return 'NCR_CLC';
        }
        
        if($municipality == 'City of Las Piñas') {
            return 'NCR_LSP';
        }

        if($municipality == 'City of Makati') {
            return 'NCR_MKT';
        }

        if($municipality == 'City of Malabon') {
            return 'NCR_MLB';
        }

        if($municipality == 'City of Mandaluyong') {
            return 'NCR_MND';
        }

        if($municipality == 'City of Manila') {
            return 'NCR_MNL';
        }

        if($municipality == 'City of Marikina') {
            return 'NCR_MRK';
        }

        if($municipality == 'City of Muntinlupa') {
            return 'NCR_MNL';
        }

        if($municipality == 'City of Navotas') {
            return 'NCR_NVT';
        }

        if($municipality == 'City of Parañaque') {
            return 'NCR_PRP';
        }

        if($municipality == 'Pasay City') {
            return 'NCR_PSY';
        }

        if($municipality == 'City of Pasig') {
            return 'NCR_PSG';
        }

        if($municipality == 'Pateros') {
            return 'NCR_PTP';
        }

        if($municipality == 'Quezon City') {
            return 'NCR_QUC';
        }

        if($municipality == 'City of San Juan') {
            return 'NCR_SNJ';
        }

        if($municipality == 'City of Taguig') {
            return 'NCR_TGT';
        }

        if($municipality == 'City of Valenzuela') {
            return 'NCR_VLZ';
        }
    }
}
