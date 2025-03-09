<?php
// index.php - Halaman utama CRUD Dosen
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Dosen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4">Daftar Dosen</h2>
        
        <form id="dosenForm" class="mb-4 flex gap-2">
            <input type="text" id="nama" placeholder="Nama" class="border p-2 rounded w-1/3" required>
            <input type="email" id="email" placeholder="Email" class="border p-2 rounded w-1/3" required>
            <input type="text" id="no_hp" placeholder="No HP" class="border p-2 rounded w-1/3" required>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded">Tambah</button>
        </form>
        
        <table class="w-full bg-white border rounded shadow-lg">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2">Nama</th>
                    <th class="p-2">Email</th>
                    <th class="p-2">No HP</th>
                    <th class="p-2">Aksi</th>
                </tr>
            </thead>
            <tbody id="dosenList"></tbody>
        </table>
    </div>

    <script>
        const apiUrl = 'http://10.33.102.175/sait_project_api/tugas/dosen_api.php'; 

        function fetchDosen() {
            fetch(apiUrl)
                .then(res => res.json())
                .then(data => {
                    const dosenList = document.getElementById('dosenList');
                    dosenList.innerHTML = '';
                    if (data.status === 1) {
                        data.data.forEach(dosen => {
                            dosenList.innerHTML += `
                                <tr class='border-b'>
                                    <td class='p-2'>${dosen.nama}</td>
                                    <td class='p-2'>${dosen.email}</td>
                                    <td class='p-2'>${dosen.no_hp}</td>
                                    <td class='p-2'>
                                        <button onclick="hapusDosen(${dosen.id})" class='bg-red-500 text-white p-1 rounded'>Hapus</button>
                                    </td>
                                </tr>`;
                        });
                    }
                });
        }

        document.getElementById('dosenForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const nama = document.getElementById('nama').value;
            const email = document.getElementById('email').value;
            const no_hp = document.getElementById('no_hp').value;
            
            fetch(apiUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nama, email, no_hp })
            }).then(() => {
                fetchDosen();
                document.getElementById('dosenForm').reset();
            });
        });
        
        function hapusDosen(id) {
            fetch(`${apiUrl}/${id}`, { method: 'DELETE' })
                .then(() => fetchDosen());
        }

        fetchDosen();
    </script>
</body>
</html>