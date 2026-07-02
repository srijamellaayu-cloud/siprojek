<?php

use App\Models\User;
use App\Models\Administrasi\Penawaran;

test('dashboard redirect works correctly for each role', function () {
    $admin = User::factory()->create(['role' => 'administrasi']);
    $eksekutif = User::factory()->create(['role' => 'eksekutif']);
    $keuangan = User::factory()->create(['role' => 'keuangan']);

    // Admin redirection
    $response = $this->actingAs($admin)->get('/dashboard');
    $response->assertRedirect(route('administrasi.dashboard'));

    // Eksekutif redirection
    $response = $this->actingAs($eksekutif)->get('/dashboard');
    $response->assertRedirect(route('eksekutif.dashboard'));

    // Keuangan redirection
    $response = $this->actingAs($keuangan)->get('/dashboard');
    $response->assertRedirect(route('keuangan.dashboard'));
});

test('administrasi can access administrasi dashboard and resources', function () {
    $admin = User::factory()->create(['role' => 'administrasi']);

    $response = $this->actingAs($admin)->get(route('administrasi.dashboard'));
    $response->assertOk();

    $response = $this->actingAs($admin)->get(route('administrasi.penawaran.index'));
    $response->assertOk();

    $response = $this->actingAs($admin)->get(route('administrasi.deal.index'));
    $response->assertOk();
});

test('eksekutif can access eksekutif dashboard and read-only resources', function () {
    $eksekutif = User::factory()->create(['role' => 'eksekutif']);

    $response = $this->actingAs($eksekutif)->get(route('eksekutif.dashboard'));
    $response->assertOk();

    $response = $this->actingAs($eksekutif)->get(route('eksekutif.penawaran.index'));
    $response->assertOk();

    $response = $this->actingAs($eksekutif)->get(route('eksekutif.deal.index'));
    $response->assertOk();
});

test('keuangan can access keuangan dashboard and deal index, but cannot access penawaran', function () {
    $keuangan = User::factory()->create(['role' => 'keuangan']);

    $response = $this->actingAs($keuangan)->get(route('keuangan.dashboard'));
    $response->assertOk();

    $response = $this->actingAs($keuangan)->get(route('keuangan.deal.index'));
    $response->assertOk();

    // Check that keuangan cannot access eksekutif penawaran routes
    $response = $this->actingAs($keuangan)->get(route('eksekutif.penawaran.index'));
    $response->assertStatus(403);
});

test('keuangan can access tagihan index and see completed deals', function () {
    $keuangan = User::factory()->create(['role' => 'keuangan']);

    // Create a completed deal (Disetujui, has tasks, all tasks Done)
    $completedDeal = Penawaran::create([
        'nama_proyek' => 'Completed Project',
        'mitra' => 'Mitra A',
        'status' => 'Disetujui',
        'tanggal' => now('Asia/Jakarta'),
    ]);
    $completedDeal->tasks()->create([
        'nama_tugas' => 'Task 1',
        'status' => 'Done',
        'tanggal_tugas' => now('Asia/Jakarta'),
    ]);

    // Create an uncompleted deal (Disetujui, has tasks, some tasks not Done)
    $uncompletedDeal = Penawaran::create([
        'nama_proyek' => 'Uncompleted Project',
        'mitra' => 'Mitra B',
        'status' => 'Disetujui',
        'tanggal' => now('Asia/Jakarta'),
    ]);
    $uncompletedDeal->tasks()->create([
        'nama_tugas' => 'Task 2',
        'status' => 'On Progress',
        'tanggal_tugas' => now('Asia/Jakarta'),
    ]);

    // Create a deal without tasks
    $noTasksDeal = Penawaran::create([
        'nama_proyek' => 'No Tasks Project',
        'mitra' => 'Mitra C',
        'status' => 'Disetujui',
        'tanggal' => now('Asia/Jakarta'),
    ]);

    // Create a penawaran that is not disetujui (e.g. pending) but all tasks Done (should not show up)
    $pendingDeal = Penawaran::create([
        'nama_proyek' => 'Pending Project',
        'mitra' => 'Mitra D',
        'status' => 'Draft',
        'tanggal' => now('Asia/Jakarta'),
    ]);
    $pendingDeal->tasks()->create([
        'nama_tugas' => 'Task 3',
        'status' => 'Done',
        'tanggal_tugas' => now('Asia/Jakarta'),
    ]);

    $response = $this->actingAs($keuangan)->get(route('keuangan.tagihan.index'));
    $response->assertOk();
    $response->assertSee('Completed Project');
    $response->assertDontSee('Uncompleted Project');
    $response->assertDontSee('No Tasks Project');
    $response->assertDontSee('Pending Project');
});

test('unauthorized cross-role access is blocked', function () {
    $admin = User::factory()->create(['role' => 'administrasi']);
    $eksekutif = User::factory()->create(['role' => 'eksekutif']);
    $keuangan = User::factory()->create(['role' => 'keuangan']);

    // Admin tries to access eksekutif and keuangan dashboards
    $response = $this->actingAs($admin)->get(route('eksekutif.dashboard'));
    $response->assertStatus(403);

    $response = $this->actingAs($admin)->get(route('keuangan.dashboard'));
    $response->assertStatus(403);

    // Eksekutif tries to access administrasi and keuangan dashboards
    $response = $this->actingAs($eksekutif)->get(route('administrasi.dashboard'));
    $response->assertStatus(403);

    $response = $this->actingAs($eksekutif)->get(route('keuangan.dashboard'));
    $response->assertStatus(403);

    // Keuangan tries to access administrasi dashboard
    $response = $this->actingAs($keuangan)->get(route('administrasi.dashboard'));
    $response->assertStatus(403);
});

test('administrasi can store and update tasks and gets redirected to prefixed route', function () {
    $admin = User::factory()->create(['role' => 'administrasi']);
    $deal = Penawaran::create([
        'nama_proyek' => 'Test Project',
        'mitra' => 'Test Partner',
        'status' => 'Disetujui',
        'tanggal' => now('Asia/Jakarta'),
    ]);

    // Test storeTask
    $response = $this->actingAs($admin)->post(route('administrasi.deal.tasks.store', $deal->id), [
        'nama_tugas' => 'New Task',
        'tanggal_tugas' => now('Asia/Jakarta')->format('Y-m-d'),
    ]);
    
    $response->assertRedirect(route('administrasi.deal.show', $deal->id));
});

test('done tasks are excluded from deadline notifications', function () {
    $admin = User::factory()->create(['role' => 'administrasi']);
    $deal = Penawaran::create([
        'nama_proyek' => 'Notification Project',
        'mitra' => 'Partner X',
        'status' => 'Disetujui',
        'tanggal' => now('Asia/Jakarta'),
    ]);

    // Create a pending task (due tomorrow) - should be included
    $pendingTask = $deal->tasks()->create([
        'nama_tugas' => 'Pending Task',
        'status' => 'On Progress',
        'tanggal_tugas' => now('Asia/Jakarta')->addDay(),
    ]);

    // Create a done task (due tomorrow) - should be excluded
    $doneTask = $deal->tasks()->create([
        'nama_tugas' => 'Done Task',
        'status' => 'Done',
        'tanggal_tugas' => now('Asia/Jakarta')->addDay(),
    ]);

    $response = $this->actingAs($admin)->get(route('api.deadline.notifications'));
    $response->assertOk();
    $data = $response->json();

    expect($data['success'])->toBeTrue()
        ->and($data['count'])->toEqual(1)
        ->and($data['tasks'][0]['id'])->toEqual($pendingTask->id);
});


