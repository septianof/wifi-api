<!DOCTYPE html>
<html>
<head>
    <title>Edit Pelanggan</title>
</head>
<body>
    <h1>Edit Pelanggan</h1>

    <form id="form-edit">
        <label>Nama:</label><br>
        <input type="text" name="nama" id="nama"><br>

        <label>Alamat:</label><br>
        <input type="text" name="alamat" id="alamat"><br>

        <label>No HP:</label><br>
        <input type="text" name="no_hp" id="no_hp"><br>

        <label>Tagihan:</label><br>
        <input type="number" name="tagihan" id="tagihan"><br><br>

        <button type="submit">Update</button>
    </form>

    <script>
        const id = new URLSearchParams(window.location.search).get('id');

        async function getData() {
            const res = await fetch(`/api/pelanggans/${id}`);
            const data = await res.json();

            document.getElementById('nama').value = data.nama;
            document.getElementById('alamat').value = data.alamat;
            document.getElementById('no_hp').value = data.no_hp ?? '';
            document.getElementById('tagihan').value = data.tagihan;
        }

        document.getElementById('form-edit').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            const res = await fetch(`/api/pelanggans/${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            if (res.ok) {
                alert('Data berhasil diperbarui');
                window.location.href = '/pelanggans';
            } else {
                alert('Gagal update data');
            }
        });

        getData();
    </script>
</body>
</html>
