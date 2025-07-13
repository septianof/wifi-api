<!DOCTYPE html>
<html>
<head>
    <title>Tambah Pelanggan</title>
</head>
<body>
    <h1>Tambah Pelanggan</h1>

    <form id="form-tambah">
        <label>Nama:</label><br>
        <input type="text" name="nama"><br>

        <label>Alamat:</label><br>
        <input type="text" name="alamat"><br>

        <label>No HP:</label><br>
        <input type="text" name="no_hp"><br>

        <label>Tagihan:</label><br>
        <input type="number" name="tagihan"><br><br>

        <button type="submit">Simpan</button>
    </form>

    <script>
        const form = document.getElementById('form-tambah');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            const data = Object.fromEntries(formData);

            const res = await fetch('/api/pelanggans', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            if (res.ok) {
                alert('Data pelanggan berhasil ditambahkan');
                window.location.href = '/pelanggans'; // arahkan ke halaman index
            } else {
                alert('Gagal menambah pelanggan');
            }
        });
    </script>
</body>
</html>
