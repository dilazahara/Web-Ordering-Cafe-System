<div
    class="notif-wrapper"
    style="position:relative;"
>

    {{-- BUTTON --}}
    <button
        id="notifBtn"
        class="hdr-btn"
        type="button"
    >

        🔔

        <span
            id="notifBadge"
            class="notif-badge"
            style="display:none;"
        >
            0
        </span>

    </button>

    {{-- DROPDOWN --}}
    <div
        id="notifDropdown"
        style="
            display:none;
            position:absolute;
            top:52px;
            right:0;
            width:320px;
            background:white;
            border:1px solid #ddd;
            border-radius:14px;
            box-shadow:0 10px 30px rgba(0,0,0,.12);
            z-index:99999;
            overflow:hidden;
        "
    >

        {{-- HEADER --}}
        <div style="
            padding:14px;
            border-bottom:1px solid #eee;
            font-weight:700;
            background:#f8fafc;
        ">
            🔔 Notifications
        </div>

        {{-- LIST --}}
        <div
            id="notifList"
            style="
                max-height:400px;
                overflow-y:auto;
            "
        >

            <div style="
                padding:20px;
                text-align:center;
                color:#888;
            ">
                Tidak ada notifikasi
            </div>

        </div>

    </div>

</div>

<script>

const notifBtn      = document.getElementById('notifBtn');
const notifDropdown = document.getElementById('notifDropdown');
const notifBadge    = document.getElementById('notifBadge');
const notifList     = document.getElementById('notifList');


// ======================
// TOGGLE DROPDOWN
// ======================

notifBtn.addEventListener('click', function(e){

    e.stopPropagation();

    if (
        notifDropdown.style.display === 'block'
    ) {

        notifDropdown.style.display = 'none';

    } else {

        notifDropdown.style.display = 'block';

    }

});


// ======================
// CLOSE CLICK OUTSIDE
// ======================

document.addEventListener('click', function(e){

    if (
        !e.target.closest('.notif-wrapper')
    ) {

        notifDropdown.style.display = 'none';

    }

});


// ======================
// MARK AS READ
// ======================

async function markAsRead(id)
{
    try {

        await fetch(
            `/notifications/${id}/read`,
            {
                method: 'PATCH',
                headers: {

                    'X-CSRF-TOKEN':
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,

                    'Accept':
                        'application/json',

                }
            }
        );

    } catch(err) {

        console.log(err);

    }
}


// ======================
// LOAD NOTIFICATIONS
// ======================

async function loadNotifications()
{
    try {

        const res = await fetch('/notifications');

        const data = await res.json();

        notifBadge.innerText =
            data.unread_count;

        notifBadge.style.display =
            data.unread_count > 0
                ? 'flex'
                : 'none';


        // EMPTY
        if (
            data.notifications.length === 0
        ) {

            notifList.innerHTML = `
                <div style="
                    padding:20px;
                    text-align:center;
                    color:#888;
                ">
                    Tidak ada notifikasi
                </div>
            `;

            return;

        }

        notifList.innerHTML = '';



        // ======================
        // LOOP NOTIF
        // ======================

        data.notifications.forEach(notif => {

            let notifUrl = '#';


            // ======================
            // ROLE BASED URL
            // ======================

            if (
                notif.target_role === 'kasir'
            ) {

                notifUrl =
                    '/kasir/pesanan';

            }

            else if (
                notif.target_role === 'dapur'
            ) {

                notifUrl =
                    '/dapur/pesanan';

            }

            else if (
                notif.target_role === 'pelayan'
            ) {

                notifUrl =
                    '/pelayan/antar';

            }

            else if (
                notif.target_role === 'admin'
            ) {

                notifUrl =
                    '/admin/dashboard';

            }


            // ======================
            // RENDER NOTIF
            // ======================

            notifList.innerHTML += `

                <a
                    href="${notifUrl}"
                    onclick="markAsRead(${notif.id})"
                    style="
                        display:block;
                        padding:14px;
                        border-bottom:1px solid #eee;
                        background:${
                            notif.is_read
                                ? '#fff'
                                : '#f5f9ff'
                        };
                        text-decoration:none;
                        transition:.2s;
                        cursor:pointer;
                    "
                    onmouseover="
                        this.style.background='#eef4ff'
                    "
                    onmouseout="
                        this.style.background='${
                            notif.is_read
                                ? '#fff'
                                : '#f5f9ff'
                        }'
                    "
                >

                    <div style="
                        font-weight:700;
                        margin-bottom:4px;
                        color:#111827;
                    ">
                        ${notif.title}
                    </div>

                    <div style="
                        font-size:13px;
                        color:#666;
                    ">
                        ${notif.message}
                    </div>

                </a>

            `;

        });

    } catch(err) {

        console.log(err);

    }
}


// ======================
// INIT
// ======================

loadNotifications();

setInterval(loadNotifications, 5000);

</script>