<!DOCTYPE html>
<html>
<head>
    <title>Daftar Pelanggan</title>
</head>
<body>
    <h1>Daftar Pelanggan</h1>

    <!-- Notifikasi Jatuh Tempo -->
    <div id="notif-jatuh-tempo" style="background: #ffdddd; padding: 10px; margin-bottom: 10px; display: none;"></div>

    <!-- Tombol Tambah dan Reset -->
    <button onclick="tambahPelanggan()">Tambah Pelanggan</button>
    <button onclick="resetStatus()">Reset Status Bayar</button>

    <br><br>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Alamat</th>
                <th>No HP</th>
                <th>Tagihan</th>
                <th>Status Bayar</th>
                <th>Jatuh Tempo</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="tabel-pelanggan">
            <!-- Diisi JS -->
        </tbody>
    </table>

    <script>
        async function fetchData() {
            const res = await fetch('/api/pelanggan');
            const data = await res.json();

            const rows = data.data.map(pelanggan => `
                <tr>
                    <td>${pelanggan.nama}</td>
                    <td>${pelanggan.alamat}</td>
                    <td>${pelanggan.no_hp ?? '-'}</td>
                    <td>Rp ${pelanggan.tagihan}</td>
                    <td>${pelanggan.status_bayar ? 'LUNAS' : 'BELUM BAYAR'}</td>
                    <td>${pelanggan.tanggal_jatuh_tempo}</td>
                    <td>
                        <button onclick="editPelanggan(${pelanggan.id})">Edit</button>
                        <button onclick="hapusPelanggan(${pelanggan.id})">Hapus</button>
                        ${!pelanggan.status_bayar ? `<button onclick="centangBayar(${pelanggan.id})">Bayar</button>` : ''}
                    </td>
                </tr>
            `).join('');

            document.getElementById('tabel-pelanggan').innerHTML = rows;
        }

        async function resetStatus() {
            if (!confirm("Reset semua status bayar?")) return;
            await fetch('/api/pelanggan/reset-status', { method: 'POST' });
            alert('Semua status telah di-reset');
            fetchData();
            cekJatuhTempo();
        }

        async function hapusPelanggan(id) {
            if (!confirm("Yakin hapus pelanggan?")) return;
            await fetch(`/api/pelanggan/${id}`, { method: 'DELETE' });
            fetchData();
            cekJatuhTempo();
        }

        async function centangBayar(id) {
            await fetch(`/api/pelanggan/${id}/centang-bayar`, { method: 'PUT' });
            fetchData();
            cekJatuhTempo();
        }

        function tambahPelanggan() {
            window.location.href = '/pelanggan/create';
        }

        function editPelanggan(id) {
            window.location.href = `/pelanggan/edit?id=${id}`;
        }

        async function cekJatuhTempo() {
            const res = await fetch('/api/pelanggan/jatuh-tempo');
            const data = await res.json();

            if (data.length > 0) {
                const daftar = data.map(p => `<li>${p.nama} belum bayar</li>`).join('');
                document.getElementById('notif-jatuh-tempo').innerHTML = `
                    <strong>⚠️ Tagihan Jatuh Tempo:</strong>
                    <ul>${daftar}</ul>
                `;
                document.getElementById('notif-jatuh-tempo').style.display = 'block';
            } else {
                document.getElementById('notif-jatuh-tempo').style.display = 'none';
            }
        }

        // Inisialisasi
        fetchData();
        cekJatuhTempo();
    </script>
</body>
</html>
