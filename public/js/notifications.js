document.addEventListener('DOMContentLoaded', () => {
    const wrapper = document.querySelector('.notification-wrapper');

    if (!wrapper) {
        return;
    }

    const trigger = document.getElementById('notificationTrigger');
    const panel = document.getElementById('notificationPanel');
    const closeButton = document.getElementById('notificationClose');
    const markAllButton = document.getElementById('notificationMarkAll');
    const list = document.getElementById('notificationList');
    const count = document.getElementById('notificationCount');
    const summary = document.getElementById('notificationSummary');
    const listUrl = wrapper.dataset.listUrl;
    const readUrl = wrapper.dataset.readUrl;
    const readAllUrl = wrapper.dataset.readAllUrl;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    let isOpen = false;

    if (!trigger || !panel || !closeButton || !markAllButton || !list || !count || !summary) {
        return;
    }

    const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    })[char]);

    const updateBadge = (total) => {
        const value = Number(total) || 0;

        count.textContent = value > 99 ? '99+' : String(value);
        count.hidden = value === 0;
        summary.textContent = value > 0
            ? `${value} notifikasi belum dibaca`
            : 'Semua notifikasi sudah dibaca';
    };

    const renderNotifications = (notifications) => {
        if (!Array.isArray(notifications) || notifications.length === 0) {
            list.innerHTML = `
                <div class="notification-empty">
                    <span>✓</span>
                    <strong>Belum ada notifikasi</strong>
                    <small>Notifikasi baru akan muncul di sini.</small>
                </div>
            `;

            return;
        }

        list.innerHTML = notifications.map((item) => {
            const isRead = item.is_read;
            const typeClass = item.type === 'status_laporan'
                ? 'status'
                : (item.type === 'laporan_baru' ? 'new-report' : 'maintenance');
            const icon = item.type === 'status_laporan'
                ? '⚙'
                : (item.type === 'laporan_baru' ? '▤' : '🔧');

            return `
                <a href="${escapeHtml(item.url || '#')}"
                   class="notification-item ${isRead ? 'is-read' : 'is-unread'}"
                   data-notification-id="${escapeHtml(item.id)}">
                    <span class="notification-item-icon ${typeClass}" aria-hidden="true">
                        ${icon}
                    </span>
                    <span class="notification-item-content">
                        <strong>${escapeHtml(item.title)}</strong>
                        <small>${escapeHtml(item.message)}</small>
                        <time>${escapeHtml(item.created_at)}</time>
                    </span>
                    ${isRead ? '' : '<span class="unread-indicator" aria-label="Belum dibaca"></span>'}
                </a>
            `;
        }).join('');
    };

    const loadNotifications = async () => {
        try {
            const response = await fetch(listUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil notifikasi');
            }

            const data = await response.json();

            updateBadge(data.unread_count);
            renderNotifications(data.notifications);
        } catch (error) {
            console.error(error);
            summary.textContent = 'Gagal memuat notifikasi';
            list.innerHTML = `
                <div class="notification-empty notification-error">
                    Notifikasi gagal dimuat.
                </div>
            `;
        }
    };

    const postRequest = async (url) => {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error('Request notifikasi gagal');
        }

        return response.json();
    };

    const openPanel = async () => {
        isOpen = true;
        panel.hidden = false;
        trigger.setAttribute('aria-expanded', 'true');
        await loadNotifications();
    };

    const closePanel = () => {
        isOpen = false;
        panel.hidden = true;
        trigger.setAttribute('aria-expanded', 'false');
    };

    trigger.addEventListener('click', () => {
        if (isOpen) {
            closePanel();
        } else {
            openPanel();
        }
    });

    closeButton.addEventListener('click', closePanel);

    list.addEventListener('click', async (event) => {
        const item = event.target.closest('[data-notification-id]');

        if (!item) {
            return;
        }

        event.preventDefault();

        const notificationId = item.dataset.notificationId;
        const targetUrl = item.href;

        try {
            await postRequest(readUrl.replace('__ID__', encodeURIComponent(notificationId)));
        } catch (error) {
            console.error(error);
        }

        window.location.assign(targetUrl);
    });

    markAllButton.addEventListener('click', async () => {
        try {
            await postRequest(readAllUrl);
            await loadNotifications();
        } catch (error) {
            console.error(error);
        }
    });

    document.addEventListener('click', (event) => {
        if (isOpen && !wrapper.contains(event.target)) {
            closePanel();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isOpen) {
            closePanel();
        }
    });

    loadNotifications();
    window.setInterval(loadNotifications, 30000);
});
