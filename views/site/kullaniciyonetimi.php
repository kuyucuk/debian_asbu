<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kullanıcı Yönetimi</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            max-width: 1600px;
            margin: 0 auto;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #793657;
        }
        .filter-section {
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        .filter-section select {
            padding: 8px 12px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .user-table th, .user-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .user-table th {
            background-color: #793657;
            color: white;
        }
        .user-table tr:hover {
            background-color: #f5f5f5;
        }
        .back-link {
            margin-top: 20px;
            text-align: center;
        }
        .back-link a {
            color: #793657;
            text-decoration: none;
            font-weight: bold;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
        .pagination {
            text-align: center;
            margin-top: 20px;
        }
        .pagination button {
            background: #d9b3c4;
            border: 1px solid #a0527d;
            padding: 5px 10px;
            margin: 2px;
            cursor: pointer;
            color: #793657;
        }
        .pagination button.active {
            background: #793657;
            color: white;
            font-weight: bold;
        }
        .rows-per-page {
            margin-bottom: 20px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="panel-container">
        <h1>Kullanıcı Yönetimi</h1>

        <!-- Filtreleme Alanı -->
        <div class="filter-section">
            <select id="sicilno-filter"><option value="all">Sicil No (Tümü)</option></select>
            <select id="name-filter"><option value="all">Ad (Tümü)</option></select>
            <select id="surname-filter"><option value="all">Soyad (Tümü)</option></select>
            <select id="title-filter"><option value="all">Ünvan (Tümü)</option></select>
            <select id="department-filter"><option value="all">Birim (Tümü)</option></select>
        </div>

        <!-- Satır Sayısı -->
        <div class="rows-per-page">
            <label for="rows-per-page-select">Sayfada Gösterilecek Kayıt Sayısı:</label>
            <select id="rows-per-page-select">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="20">20</option>
                <option value="50">50</option>
            </select>
        </div>

        <!-- Kullanıcı Tablosu -->
        <table class="user-table">
            <thead>
                <tr>
                    <th>Sıra No</th>
                    <th>Sicil No</th>
                    <th>Ad</th>
                    <th>Soyad</th>
                    <th>Ünvan</th>
                    <th>Birim</th>
                </tr>
            </thead>
            <tbody id="user-table-body">
            <?php
            $personnelCount = 5000;

            $names = ['Ahmet', 'Mehmet', 'Ayşe', 'Fatma', 'Ali', 'Veli', 'Zeynep', 'Emre', 'Can', 'Elif', 'Tolga', 'Merve', 'Burcu', 'Cem', 'Deniz', 'Ece', 'Gizem', 'Hüseyin', 'İlayda', 'Kaan', 'Lara', 'Mert', 'Nisa', 'Oğuz', 'Pelin', 'Seda', 'Tuna', 'Uğur', 'Vildan', 'Yasin'];
            $surnames = ['Yılmaz', 'Kaya', 'Demir', 'Çelik', 'Arslan', 'Koç', 'Şahin', 'Yıldız', 'Aksoy', 'Öztürk', 'Aydın', 'Polat', 'Kurt', 'Tekin', 'Çetin', 'Kara', 'Aslan', 'Güneş', 'Özdemir', 'Yavuz', 'Sönmez', 'Acar', 'Duman', 'Erdem', 'Kaya', 'Uçar', 'Yücel', 'Korkmaz', 'Turan'];
            $titles = ['Şube Müdürü','Enstitü Müdürü', 'Şef', 'Daire Başkanı', 'Koordinatör','Memur','Genel Sekreter','Dekan','Yardımcı Personel','Teknik Personel'];
            $departments = ['Personel Daire Başkanlığı', 'Mali İşler Daire Başkanlığı', 'Bilgi İşlem Daire Başkanlığı', 'Rektörlük', 'Hukuk Fakültesi', 'İktisadi ve İdari Bilimler Fakültesi', 'Erasmus Koordinatörlüğü', 'Sosyal Bilimler Enstitüsü'];

            $sicilList = [];
            for ($i = 0; $i < $personnelCount; $i++) {
                $sicilList[] = 'A' . rand(10000, 99999);
            }

            $personnel = [];
            $uniqueRoles = [
                'Genel Sekreter' => false,
                'Dekan' => [],
                'Daire Başkanı' => []
            ];

            foreach ($sicilList as $sicilNo) {
                $name = $names[array_rand($names)];
                $surname = $surnames[array_rand($surnames)];
                $gorevSayisi = rand(1, 3);

                for ($i = 0; $i < $gorevSayisi; $i++) {
                    $title = $titles[array_rand($titles)];
                    $department = $departments[array_rand($departments)];

                    if ($title === 'Genel Sekreter') {
                        if ($uniqueRoles['Genel Sekreter']) continue;
                        $uniqueRoles['Genel Sekreter'] = true;
                        $department = 'Rektörlük'; // Genel Sekreter'in birimi her zaman Rektörlük
                    } elseif ($title === 'Dekan') {
                        if (in_array($department, $uniqueRoles['Dekan'])) continue;
                        $uniqueRoles['Dekan'][] = $department;
                    } elseif ($title === 'Daire Başkanı') {
                        if (in_array($department, $uniqueRoles['Daire Başkanı'])) continue;
                        $uniqueRoles['Daire Başkanı'][] = $department;
                    }



                    $personnel[] = [
                        'sicilNo' => $sicilNo,
                        'name' => $name,
                        'surname' => $surname,
                        'title' => $title,
                        'department' => $department
                    ];
                }
            }

            $counter = 1;
            foreach ($personnel as $p) {
                echo "<tr data-sicilno='{$p['sicilNo']}' data-name='{$p['name']}' data-surname='{$p['surname']}' data-title='{$p['title']}' data-department='{$p['department']}'>
                        <td>{$counter}</td>
                        <td>{$p['sicilNo']}</td>
                        <td>{$p['name']}</td>
                        <td>{$p['surname']}</td>
                        <td>{$p['title']}</td>
                        <td>{$p['department']}</td>
                    </tr>";
                $counter++;
            }
            ?>

            </tbody>
        </table>

        <!-- Sayfalama -->
        <div class="pagination" id="pagination"></div>

        <div class="back-link">
            <a href="/index.php?r=site/yoneticipaneli">← Yönetici Paneline Dön</a>
        </div>
    </div>

    <script>
        const rows = Array.from(document.querySelectorAll(".user-table tbody tr"));
        const pagination = document.getElementById("pagination");
        const rowsPerPageSelect = document.getElementById("rows-per-page-select");

        let rowsPerPage = parseInt(rowsPerPageSelect.value);
        let totalPages = Math.ceil(rows.length / rowsPerPage);
        let currentPageGroup = 1;
        const maxPagesPerGroup = 10;

        function showPage(page) {
            rows.forEach((row, index) => {
                row.style.display = (index >= (page - 1) * rowsPerPage && index < page * rowsPerPage) ? "" : "none";
            });
            document.querySelectorAll(".pagination button").forEach(btn => btn.classList.remove("active"));
            const activeButton = document.getElementById("page" + page);
            if (activeButton) activeButton.classList.add("active");
        }

        function createPagination() {
            pagination.innerHTML = "";
            totalPages = Math.ceil(rows.length / rowsPerPage);
            const startPage = (currentPageGroup - 1) * maxPagesPerGroup + 1;
            const endPage = Math.min(startPage + maxPagesPerGroup - 1, totalPages);

            if (currentPageGroup > 1) {
                const prevButton = document.createElement("button");
                prevButton.innerText = "<<";
                prevButton.onclick = () => {
                    currentPageGroup--;
                    createPagination();
                    showPage((currentPageGroup - 1) * maxPagesPerGroup + 1);
                };
                pagination.appendChild(prevButton);
            }

            for (let i = startPage; i <= endPage; i++) {
                const btn = document.createElement("button");
                btn.innerText = i;
                btn.id = "page" + i;
                btn.onclick = () => showPage(i);
                pagination.appendChild(btn);
            }

            if (endPage < totalPages) {
                const nextButton = document.createElement("button");
                nextButton.innerText = ">>";
                nextButton.onclick = () => {
                    currentPageGroup++;
                    createPagination();
                    showPage((currentPageGroup - 1) * maxPagesPerGroup + 1);
                };
                pagination.appendChild(nextButton);
            }
        }

        rowsPerPageSelect.addEventListener("change", () => {
            rowsPerPage = parseInt(rowsPerPageSelect.value);
            currentPageGroup = 1;
            createPagination();
            showPage(1);
        });

        createPagination();
        showPage(1);
    </script>

    <script>
        const sicilFilter = document.getElementById("sicilno-filter");
        const nameFilter = document.getElementById("name-filter");
        const surnameFilter = document.getElementById("surname-filter");
        const titleFilter = document.getElementById("title-filter");
        const departmentFilter = document.getElementById("department-filter");

        const allRows = Array.from(document.querySelectorAll(".user-table tbody tr"));

        const filters = {
            sicilno: sicilFilter,
            name: nameFilter,
            surname: surnameFilter,
            title: titleFilter,
            department: departmentFilter
        };

        function getSelectedFilters() {
            return {
                sicilno: sicilFilter.value,
                name: nameFilter.value,
                surname: surnameFilter.value,
                title: titleFilter.value,
                department: departmentFilter.value
            };
        }

        function updateFilterOptions() {
            const selected = getSelectedFilters();

            // Geçerli filtrelerle eşleşen satırları bul
            const matchingRows = allRows.filter(row => {
                return Object.entries(selected).every(([key, value]) => {
                    return value === "all" || row.dataset[key] === value;
                });
            });

            Object.entries(filters).forEach(([key, select]) => {
                const prevValue = select.value;
                select.innerHTML = `<option value="all">${select.options[0].textContent}</option>`;

                const values = [...new Set(matchingRows.map(row => row.dataset[key]))].sort();
                values.forEach(val => {
                    const option = document.createElement("option");
                    option.value = val;
                    option.textContent = val;
                    select.appendChild(option);
                });

                // Önceki seçim tekrar varsa koru
                if ([...select.options].some(opt => opt.value === prevValue)) {
                    select.value = prevValue;
                }
            });
        }

        function filterTable() {
            const selected = getSelectedFilters();

            rows.length = 0;
            allRows.forEach(row => {
                const matches = Object.entries(selected).every(([key, value]) => {
                    return value === "all" || row.dataset[key] === value;
                });

                row.style.display = matches ? "" : "none";
                if (matches) rows.push(row);
            });

            updateFilterOptions(); // diğer filtreleri güncelle
            currentPageGroup = 1;
            createPagination();
            showPage(1);
        }

        Object.values(filters).forEach(select => {
            select.addEventListener("change", filterTable);
        });

        updateFilterOptions();
        filterTable();
    </script>

</body>
</html>
