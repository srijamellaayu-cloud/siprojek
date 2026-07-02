<?php

use App\Models\Administrasi\Penawaran;

test('it calculates progress correctly with late penalties', function () {
    // 1. Create a deal project
    $deal = Penawaran::create([
        'nama_proyek' => 'Test Project',
        'mitra' => 'Test Partner',
        'status' => 'Disetujui',
        'tanggal' => now('Asia/Jakarta'),
    ]);

    // 2. Create tasks with different states
    // Total tasks = 5.
    // Done tasks = 3 (base progress = 60%)
    // Pending tasks = 2:
    // - Task 4: Not Done, deadline in the future (no penalty)
    // - Task 5: Not Done, deadline in the past by 3 days.
    // Weight per task = 100 / 5 = 20%
    // Task 5 days late = 3 days. Penalty per day = 2% * 3 = 6% of its weight.
    // Penalty fraction = 6% (<= 50% max limit).
    // Penalty = 20% * 6% = 1.2%
    // Final progress = 60% - 1.2% = 58.8% -> rounded to 59%

    // Done tasks
    for ($i = 0; $i < 3; $i++) {
        $deal->tasks()->create([
            'nama_tugas' => "Done Task $i",
            'status' => 'Done',
            'tanggal_tugas' => now('Asia/Jakarta')->subDays(5),
        ]);
    }

    // Pending task on time (deadline tomorrow)
    $deal->tasks()->create([
        'nama_tugas' => 'Pending Task On Time',
        'status' => 'On Progress',
        'tanggal_tugas' => now('Asia/Jakarta')->addDay(),
    ]);

    // Pending task late by 3 days (deadline 3 days ago)
    $deal->tasks()->create([
        'nama_tugas' => 'Pending Task Late',
        'status' => 'On Progress',
        'tanggal_tugas' => now('Asia/Jakarta')->subDays(3),
    ]);

    $details = $deal->progress_details;

    expect($details['base'])->toEqual(54.0)
        ->and($details['penalty'])->toEqual(1.08)
        ->and($details['final'])->toEqual(53);

    // Let's add a highly late task (late by 30 days, penalty capped at 50% of weight)
    // Total tasks is now 6. Weight per normal task = 90 / 6 = 15%
    // Completed tasks = 3 (base progress = 45%)
    // Pending tasks = 3:
    // - Task 4: future (no penalty)
    // - Task 5: 3 days late. Penalty fraction = 6%. Penalty = 15% * 6% = 0.9%
    // - Task 6: 30 days late. Penalty fraction = 30 * 2% = 60%, capped at 50%. Penalty = 15% * 50% = 7.5%
    // Total penalty = 0.9% + 7.5% = 8.4%
    // Final progress = 45% - 8.4% = 36.6% -> rounded to 37%
    $deal->tasks()->create([
        'nama_tugas' => 'Pending Task Very Late',
        'status' => 'On Progress',
        'tanggal_tugas' => now('Asia/Jakarta')->subDays(30),
    ]);

    // Refresh model relation
    $deal->unsetRelation('tasks');

    $details2 = $deal->progress_details;
    expect($details2['base'])->toEqual(45.0)
        ->and($details2['penalty'])->toEqual(8.4)
        ->and($details2['final'])->toEqual(37);
});

