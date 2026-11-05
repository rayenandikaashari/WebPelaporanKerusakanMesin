<?php

namespace Tests\Feature;

use App\Models\LaporanKerusakan;
use App\Models\Mesin;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_only_see_and_mark_their_own_notifications(): void
    {
        $user = $this->userWithRole('operator');
        $otherUser = $this->userWithRole('operator');

        $notification = $this->notificationFor($user);
        $otherNotification = $this->notificationFor($otherUser);

        $this->actingAs($user)
            ->getJson(route('notifications.index'))
            ->assertOk()
            ->assertJsonPath('unread_count', 1)
            ->assertJsonCount(1, 'notifications')
            ->assertJsonPath('notifications.0.id', $notification->id);

        $this->postJson(route('notifications.read', $otherNotification->id))
            ->assertNotFound();

        $this->postJson(route('notifications.read-all'))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertNotNull($notification->fresh()->read_at);
        $this->assertNull($otherNotification->fresh()->read_at);
    }

    public function test_new_operator_report_notifies_all_technicians_and_supervisors(): void
    {
        $operator = $this->userWithRole('operator');
        $technician = $this->userWithRole('teknisi');
        $supervisor = $this->userWithRole('supervisor');
        $machine = $this->machine();

        $this->actingAs($operator)
            ->post(route('operator.simpan-laporan'), [
                'mesin_id' => $machine->id,
                'judul' => 'Motor panas',
                'deskripsi' => 'Suhu motor meningkat saat digunakan.',
            ])
            ->assertRedirect(route('operator.laporan'));

        $report = LaporanKerusakan::query()->firstOrFail();

        $this->assertDatabaseCount('user_notifications', 2);
        $this->assertDatabaseHas('user_notifications', [
            'user_id' => $technician->id,
            'laporan_kerusakan_id' => $report->id,
            'type' => 'laporan_baru',
            'url' => route('teknisi.laporan.detail', $report->id),
        ]);
        $this->assertDatabaseHas('user_notifications', [
            'user_id' => $supervisor->id,
            'laporan_kerusakan_id' => $report->id,
            'type' => 'laporan_baru',
            'url' => route('supervisor.laporan'),
        ]);
    }

    public function test_technician_status_changes_create_events_only_when_status_changes(): void
    {
        $operator = $this->userWithRole('operator');
        $technician = $this->userWithRole('teknisi');
        $supervisor = $this->userWithRole('supervisor');
        $report = $this->reportFor($operator);

        $this->actingAs($technician)
            ->post(route('teknisi.laporan.status', $report->id), [
                'status' => 'Sedang Diperbaiki',
            ])
            ->assertRedirect(route('teknisi.laporan.detail', $report->id));

        $this->assertDatabaseCount('user_notifications', 2);
        $this->assertDatabaseHas('user_notifications', [
            'user_id' => $operator->id,
            'laporan_kerusakan_id' => $report->id,
            'type' => 'status_laporan',
            'url' => route('operator.laporan').'#laporan-'.$report->id,
        ]);
        $this->assertDatabaseHas('user_notifications', [
            'user_id' => $supervisor->id,
            'laporan_kerusakan_id' => $report->id,
            'type' => 'status_laporan',
        ]);

        $this->post(route('teknisi.laporan.status', $report->id), [
            'status' => 'Sedang Diperbaiki',
        ])->assertRedirect(route('teknisi.laporan.detail', $report->id));

        $this->assertDatabaseCount('user_notifications', 2);

        $this->post(route('teknisi.laporan.status', $report->id), [
            'status' => 'Selesai Diperbaiki',
        ])->assertRedirect(route('teknisi.laporan.detail', $report->id));

        $this->assertDatabaseCount('user_notifications', 4);
    }

    public function test_technician_maintenance_records_notify_supervisors(): void
    {
        $technician = $this->userWithRole('teknisi');
        $supervisor = $this->userWithRole('supervisor');
        $machine = $this->machine();

        $this->actingAs($technician)
            ->post(route('teknisi.maintenance.simpan'), [
                'tanggal' => '2026-11-05',
                'mesin_id' => $machine->id,
                'keterangan' => 'Pemeriksaan rutin selesai.',
            ])
            ->assertRedirect(route('teknisi.maintenance.index', [
                'tanggal' => '2026-11-05',
            ]));

        $this->assertDatabaseHas('user_notifications', [
            'user_id' => $supervisor->id,
            'laporan_kerusakan_id' => null,
            'type' => 'maintenance',
            'url' => route('supervisor.maintenance'),
        ]);
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->forceFill(['role' => $role])->save();

        return $user;
    }

    private function notificationFor(User $user): UserNotification
    {
        return UserNotification::create([
            'user_id' => $user->id,
            'type' => 'laporan_baru',
            'title' => 'Laporan kerusakan baru',
            'message' => 'Ada laporan baru.',
        ]);
    }

    private function machine(): Mesin
    {
        return Mesin::create([
            'kode' => 'M-'.fake()->unique()->numerify('####'),
            'nama' => 'Mesin Uji',
            'aktif' => true,
        ]);
    }

    private function reportFor(User $operator): LaporanKerusakan
    {
        $machine = $this->machine();

        return LaporanKerusakan::create([
            'mesin_id' => $machine->id,
            'operator_id' => $operator->id,
            'judul' => 'Kerusakan uji',
            'deskripsi' => 'Deskripsi pengujian.',
            'status' => 'Menunggu Ditangani',
        ]);
    }
}
