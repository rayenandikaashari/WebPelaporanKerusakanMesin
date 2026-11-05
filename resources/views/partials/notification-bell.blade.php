<div class="notification-wrapper"
     data-list-url="{{ route('notifications.index') }}"
     data-read-url="{{ route('notifications.read', ['id' => '__ID__']) }}"
     data-read-all-url="{{ route('notifications.read-all') }}">

    <button type="button"
            class="notification-trigger"
            id="notificationTrigger"
            aria-label="Notifikasi"
            aria-expanded="false"
            aria-controls="notificationPanel">
        <span aria-hidden="true">🔔</span>
        <span class="notification-count"
              id="notificationCount"
              hidden>0</span>
    </button>

    <section class="notification-panel"
             id="notificationPanel"
             aria-label="Daftar notifikasi"
             hidden>

        <div class="notification-panel-header">
            <div>
                <strong>Notifikasi</strong>
                <small id="notificationSummary">Memuat notifikasi...</small>
            </div>

            <button type="button"
                    class="notification-close"
                    id="notificationClose"
                    aria-label="Tutup notifikasi">
                ×
            </button>
        </div>

        <div class="notification-actions">
            <button type="button" id="notificationMarkAll">
                Tandai semua dibaca
            </button>
        </div>

        <div class="notification-list" id="notificationList">
            <div class="notification-empty">
                Memuat notifikasi...
            </div>
        </div>
    </section>
</div>
