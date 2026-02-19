<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cities;
use Illuminate\Support\Facades\Log;

class ImportCitiesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:cities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import cities from geonames file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Importing cities...');
        $filePath = 'c:\Users\Lenovo\Downloads\geo\cities500\cities500.txt';

        if (!file_exists($filePath)) {
            $this->error('File not found: ' . $filePath);
            return 1;
        }

        $file = fopen($filePath, 'r');

        if (!$file) {
            $this->error('Could not open file: ' . $filePath);
            return 1;
        }

        while (($line = fgets($file)) !== false) {
            $data = explode("\t", $line);

            try {
                Cities::create([
                    'geoname_id'   => $data[0],
                    'name'         => $data[1],
                    'latitude'     => $data[4],
                    'longitude'    => $data[5],
                    'country_code' => $data[8],
                    'state_code'   => $data[10],
                    'states_code'  => $data[11],
                    'population'   => $data[14],
                ]);
            } catch (\Exception $e) {
                Log::error('Error importing line: ' . $line . ' - ' . $e->getMessage());
                $this->error('Error importing line: ' . $line);
            }
        }

        fclose($file);

        $this->info('Cities imported successfully.');
        return 0;
    }
}
