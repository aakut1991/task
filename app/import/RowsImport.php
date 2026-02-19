<?php

namespace App\Imports;

use App\Models\Row;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;
// use App\Events\RowCreatedEvent; // Импорт события для Laravel Echo

class RowsImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
{
    /**
     * @param string $importId Идентификатор для отслеживания прогресса в Redis
     */
    public function __construct(public string $importId) {}

    /**
     * Обработка каждой строки из Excel
     */
    public function model(array $row)
    {
        // Инкремент счетчика в Redis для отслеживания прогресса
        Redis::incr("import_progress:{$this->importId}");

        // Создание записи в базе данных
        $newRow = Row::create([
            'external_id' => $row['id'],
            'name'        => $row['name'],
            'date'        => Carbon::parse($row['date']),
        ]);


        return $newRow;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}