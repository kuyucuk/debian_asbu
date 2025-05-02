<!DOCTYPE html>
<html lang="tr">
<head>
<?php
$this->title = 'Yönetim Paneli';
?>    <meta charset="UTF-8">
    <title>$title</title>
    <link rel="stylesheet" href="styles.css">
    
</head>
<body>
    
    <div class="panel-container">
        <h1>Alt Birimlerde Çalışan Personel Listesi</h1>


        <!-- Filtreleme Alanı -->
        <div class="filter-section">
            <select id="sicilno-filter"><option value="all">Sicil No (Tümü)</option></select>
            <select id="name-filter"><option value="all">Ad (Tümü)</option></select>
            <select id="surname-filter"><option value="all">Soyad (Tümü)</option></select>
            <select id="title-filter"><option value="all">Ünvan (Tümü)</option></select>
            <select id="department-filter"><option value="all">Birim (Tümü)</option></select>
        </div>

        <!-- Filtreleme Alanı -->
<div class="filter-section">
    <input type="text" id="search-input" placeholder="Personel Ara...">
</div>

        <!-- JavaScript: Arama Özelliği -->
        <script>
        const searchInput = document.getElementById("search-input");

        searchInput.addEventListener("input", () => {
            const searchTerm = searchInput.value.toLowerCase();
            rows.length = 0;
            allRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                const match = rowText.includes(searchTerm);
                row.style.display = match ? "" : "none";
                if (match) rows.push(row);
        });
        currentPageGroup = 1;
        createPagination();
        showPage(1);
        });
        </script>

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
                    <th data-sort-key="index">Sıra No<span class="sort-indicator"></span></th>
                    <th data-sort-key="sicilno">Sicil No<span class="sort-indicator"></span></th>
                    <th data-sort-key="name">Ad<span class="sort-indicator"></span></th>
                    <th data-sort-key="surname">Soyad<span class="sort-indicator"></span></th>
                    <th data-sort-key="title">Ünvan<span class="sort-indicator"></span></th>
                    <th data-sort-key="department">Birim<span class="sort-indicator"></span></th>
                </tr>
            </thead>    
            
            <tbody id="user-table-body">
            <?php
            $personnelCount = 5000;

            $names = ['Tolga','Merve','Ahmet', 'Mehmet', 'Ayşe', 'Fatma', 'Ali', 'Veli', 'Zeynep', 'Emre', 'Can', 'Elif', 'Burcu', 'Cem', 'Deniz', 'Ece', 'Gizem', 'Hüseyin', 'İlayda', 'Kaan', 'Lara', 'Mert', 'Nisa', 'Oğuz', 'Pelin', 'Seda', 'Tuna', 'Uğur', 'Vildan', 'Yasin', 'Zehra', 'Aylin', 'Berk', 'Cansu', 'Derya', 'Efe', 'Furkan', 'Gökhan', 'Hakan', 'Işıl', 'Jale', 'Kerem', 'Leman', 'Melek', 'Nehir', 'Oğuzhan', 'Pınar', 'Rabia', 'Sinem', 'Tamer', 'Ulaş', 'Veysel', 'Yasemin', 'Ziya', 'Aysel', 'Berkay', 'Cemre', 'Dilan', 'Eylül', 'Fikret', 'Gizem', 'Hülya', 'İbrahim', 'Kübra', 'Lütfiye', 'Melek', 'Nihal', 'Oğuzhan', 'Pınar', 'Rabia', 'Seda', 'Tamer', 'Uğur', 'Veysel', 'Yasemin', 'Ziya', 'Aysel', 'Berkay', 'Cemre', 'Dilan', 'Eylül', 'Fikret', 'Gizem', 'Hülya', 'İbrahim', 'Kübra', 'Lütfiye', 'Melek', 'Nihal','Beste', 'Seher', 'Zafer', 'Nuriye','Esra'];
            $surnames = ['Kuyucuk','Yaldız','Yılmaz', 'Kaya', 'Demir', 'Çelik', 'Arslan', 'Koç', 'Şahin', 'Yıldız', 'Aksoy', 'Öztürk', 'Aydın', 'Polat', 'Kurt', 'Tekin', 'Çetin', 'Kara', 'Aslan', 'Güneş', 'Özdemir', 'Yavuz', 'Sönmez', 'Acar', 'Duman', 'Erdem', 'Kaya', 'Uçar', 'Yücel', 'Korkmaz', 'Turan', 'Mimaroğlu', 'Altınay', 'Akbaba', 'Buldu', 'Toru', 'Baktır', 'Matur', 'Aydemir', 'Çetinkaya', 'Aydoğdu', 'Şahin'];
            $titles = ['Merkez Müdürü','Enstitü Müdürü', 'Enstitü Sekreteri', 'Daire Başkanı', 'Koordinatör','Fakülte Sekreteri','Genel Sekreter','Dekan','Yüksekokul Müdürü','Yüksekokul Sekreteri'];
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
                        $department = 'Rektörlük';
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


    </div>
        

    
    <!-- JavaScript: Sayfalama -->
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
        let currentSort = { key: null, direction: 'asc' };

        function sortTable(key) {
            const tbody = document.getElementById('user-table-body');
            const allRows = Array.from(document.querySelectorAll('.user-table tbody tr'));

            if (currentSort.key === key) {
            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
            currentSort.key = key;
            currentSort.direction = 'asc';
            }

            allRows.sort((a, b) => {
            let aValue, bValue;

            if (key === 'index') {
                aValue = parseInt(a.children[0].textContent);
                bValue = parseInt(b.children[0].textContent);
            } else {
                aValue = a.dataset[key];
                bValue = b.dataset[key];

                // Handle Turkish alphabet sorting
                const turkishCollator = new Intl.Collator('tr', { sensitivity: 'base' });
                return currentSort.direction === 'asc'
                ? turkishCollator.compare(aValue, bValue)
                : turkishCollator.compare(bValue, aValue);
            }

            return currentSort.direction === 'asc' ? aValue - bValue : bValue - aValue;
            });

            // Rebuild tbody
            tbody.innerHTML = '';
            allRows.forEach(row => tbody.appendChild(row));

            // Update sort indicators
            document.querySelectorAll('.sort-indicator').forEach(indicator => {
            indicator.textContent = '';
            });
            const activeHeader = document.querySelector(`th[data-sort-key="${key}"]`);
            activeHeader.querySelector('.sort-indicator').textContent =
            currentSort.direction === 'asc' ? '▼' : '▲';

            // Reset pagination to include all rows
            rows.length = allRows.length;
            rows.splice(0, rows.length, ...allRows);

            // Update pagination and filtering
            currentPageGroup = 1;
            createPagination();
            showPage(1);
        }

        // Add click event to headers
        document.querySelectorAll('.user-table th').forEach(header => {
            header.addEventListener('click', () => {
            const sortKey = header.dataset.sortKey;
            sortTable(sortKey);
            });
        });

        function createPagination() {
            pagination.innerHTML = "";
            totalPages = Math.ceil(rows.length / rowsPerPage);
            const startPage = (currentPageGroup - 1) * maxPagesPerGroup + 1;
            const endPage = Math.min(startPage + maxPagesPerGroup, totalPages);

            // Add "First" button
            if (currentPageGroup > 1) {
            const firstButton = document.createElement("button");
            firstButton.innerText = "|<";
            firstButton.onclick = () => {
                currentPageGroup = 1;
                createPagination();
                showPage(1);
            };
            pagination.appendChild(firstButton);
            }

            // Add "<<" button
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

            // Add page buttons
            for (let i = startPage; i <= endPage; i++) {
            const btn = document.createElement("button");
            btn.innerText = i;
            btn.id = "page" + i;
            btn.onclick = () => {
                if (i === endPage && endPage < totalPages) {
                currentPageGroup++;
                createPagination();
                } else if (i === startPage && startPage > 1) {
                currentPageGroup--;
                createPagination();
                }
                showPage(i);
            };
            pagination.appendChild(btn);
            }

            // Add ">>" button
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

            // Add "Last" button
            if (endPage < totalPages) {
            const lastButton = document.createElement("button");
            lastButton.innerText = ">|";
            lastButton.onclick = () => {
                currentPageGroup = Math.ceil(totalPages / maxPagesPerGroup);
                createPagination();
                showPage(totalPages);
            };
            pagination.appendChild(lastButton);
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

    <!-- JavaScript: Filtreleme -->
    <script>
        const sicilFilter = document.getElementById("sicilno-filter");
        const nameFilter = document.getElementById("name-filter");
        const surnameFilter = document.getElementById("surname-filter");
        const titleFilter = document.getElementById("title-filter");
        const departmentFilter = document.getElementById("department-filter");

        const allRows = Array.from(document.querySelectorAll(".user-table tbody tr"));
        const filters = { sicilno: sicilFilter, name: nameFilter, surname: surnameFilter, title: titleFilter, department: departmentFilter };

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
            const matchingRows = allRows.filter(row => Object.entries(selected).every(([key, val]) => val === "all" || row.dataset[key] === val));

            Object.entries(filters).forEach(([key, select]) => {
                const prevValue = select.value;
                select.innerHTML = `<option value="all">${select.options[0].textContent}</option>`;
                [...new Set(matchingRows.map(row => row.dataset[key]))].sort().forEach(val => {
                    const option = document.createElement("option");
                    option.value = val;
                    option.textContent = val;
                    select.appendChild(option);
                });
                if ([...select.options].some(opt => opt.value === prevValue)) {
                    select.value = prevValue;
                }
            });
        }

        function filterTable() {
            const selected = getSelectedFilters();
            rows.length = 0;
            allRows.forEach(row => {
                const match = Object.entries(selected).every(([key, val]) => val === "all" || row.dataset[key] === val);
                row.style.display = match ? "" : "none";
                if (match) rows.push(row);
            });
            updateFilterOptions();
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

    <!-- JavaScript: Satır tıklayınca yönlendirme -->
    <script>
        document.getElementById('user-table-body').addEventListener('click', function(e) {
            const row = e.target.closest('tr');
            if (row) {
                const idNo = row.dataset.idno;
                window.location.href = `kullanici.php?sicil=${encodeURIComponent(sicilNo)}`;
            }
        });
        document.getElementById('user-table-body').addEventListener('click', function(e) {
            const row = e.target.closest('tr');
            if (row) {
        const sicilNo = row.dataset.sicilno;
        const name = row.dataset.name;
        const surname = row.dataset.surname;
        const title = row.dataset.title;
        const department = row.dataset.department;
        window.location.href = `/index.php?r=site/profil&sicilno=${encodeURIComponent(sicilNo)}&name=${encodeURIComponent(name)}&surname=${encodeURIComponent(surname)}&title=${encodeURIComponent(title)}&department=${encodeURIComponent(department)}`;

    }
});
    </script>
    
</body>
</html>


