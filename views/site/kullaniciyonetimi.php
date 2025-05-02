<?php
// db.php
$pdo = new PDO("mysql:host=10.0.47.121;dbname=asbu_pbs;charset=utf8mb4", "root", "Asbu&2022*");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

require 'db.php';

// Kullanıcıları ve departmanları çek
$sql = "SELECT p.id, p.ad, p.soyad, p.unvan, p.birim, p.kurum_sicil_no,
               p.tc, p.jobrecord_tipi, p.jobrecord_alttipi, p.cinsiyet
        FROM personeller p
        WHERE p.kurum_sicil_no != '-'";

$stmt = $pdo->query($sql);
$kullanicilar = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kullanıcı Listesi</title>
    <style>
        .panel-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .filter-section {
            margin: 20px 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
        }
        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
            cursor: pointer;
        }
        th {
            background-color: #f8f9fa;
            position: sticky;
            top: 0;
        }
        .sort-indicator {
            margin-left: 5px;
            font-size: 0.8em;
        }
        .pagination {
            margin-top: 20px;
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        .pagination button {
            padding: 5px 10px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
        }
        .pagination button.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
        .rows-per-page {
            margin: 10px 0;
        }
        #search-input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    
    <div class="panel-container">
        <h1>Kullanıcı Listesi</h1>

        <!-- Filtreleme Alanı -->
        <div class="filter-section">
            <input type="text" id="search-input" placeholder="Arama...">
            <select id="name-filter"><option value="all">Ad (Tümü)</option></select>
            <select id="surname-filter"><option value="all">Soyad (Tümü)</option></select>
            <select id="title-filter"><option value="all">Ünvan (Tümü)</option></select>
            <select id="department-filter"><option value="all">Birim (Tümü)</option></select>
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
            <label>Sayfada Göster:
                <select id="rows-per-page-select">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </label>
        </div>

        <!-- Kullanıcı Tablosu -->
        <table class="user-table">
            <thead>
                <tr>
                    <th data-sort-key="index">Sıra No<span class="sort-indicator"></span></th>
                    <th data-sort-key="kurum_sicil_no">Kurum Sicil No<span class="sort-indicator"></span></th>
                    <th data-sort-key="ad">Ad<span class="sort-indicator"></span></th>
                    <th data-sort-key="soyad">Soyad<span class="sort-indicator"></span></th>
                    <th data-sort-key="unvan">Ünvan<span class="sort-indicator"></span></th>
                    <th data-sort-key="birim">Birim<span class="sort-indicator"></span></th>
                </tr>
            </thead>
            <tbody id="user-table-body">
                <?php 
                $index = 1;
                foreach ($kullanicilar as $k): 
                ?>
                <tr data-sicilno="<?= htmlspecialchars($k['kurum_sicil_no']) ?>"
                    data-name="<?= htmlspecialchars($k['ad']) ?>"
                    data-surname="<?= htmlspecialchars($k['soyad']) ?>"
                    data-title="<?= htmlspecialchars($k['unvan']) ?>"
                    data-department="<?= htmlspecialchars($k['birim']) ?>">
                    <td><?= $index++ ?></td>
                    <td><?= htmlspecialchars($k['kurum_sicil_no']) ?></td>
                    <td><?= htmlspecialchars($k['ad']) ?></td>
                    <td><?= htmlspecialchars($k['soyad']) ?></td>
                    <td><?= htmlspecialchars($k['unvan']) ?></td>
                    <td><?= htmlspecialchars($k['birim']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Sayfalama -->
        <div class="pagination" id="pagination"></div>
    </div>

    <script>
    // Ortak Değişkenler
    const allRows = Array.from(document.querySelectorAll("#user-table-body tr"));
    let rows = [...allRows];
    const pagination = document.getElementById("pagination");
    const rowsPerPageSelect = document.getElementById("rows-per-page-select");

    let rowsPerPage = parseInt(rowsPerPageSelect.value);
    let currentPageGroup = 1;
    const maxPagesPerGroup = 10;
    let currentSort = { key: null, direction: 'asc' };

    // Sayfalama Fonksiyonları
    function showPage(page) {
        const startIndex = (page - 1) * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;
        
        rows.forEach((row, index) => {
            row.style.display = (index >= startIndex && index < endIndex) ? "" : "none";
        });
        
        document.querySelectorAll(".pagination button").forEach(btn => {
            btn.classList.remove("active");
            if(btn.textContent == page) btn.classList.add("active");
        });
    }

    function createPagination() {
        pagination.innerHTML = "";
        const totalPages = Math.ceil(rows.length / rowsPerPage);
        const startPage = (currentPageGroup - 1) * maxPagesPerGroup + 1;
        const endPage = Math.min(startPage + maxPagesPerGroup - 1, totalPages);

        // Navigation Buttons
        const addButton = (text, onClick, disabled=false) => {
            const btn = document.createElement("button");
            btn.textContent = text;
            btn.onclick = onClick;
            if(disabled) btn.disabled = true;
            pagination.appendChild(btn);
        };

        // First & Previous Group
        if(currentPageGroup > 1) {
            addButton("|<", () => {
                currentPageGroup = 1;
                createPagination();
                showPage(1);
            });
            addButton("<<", () => {
                currentPageGroup--;
                createPagination();
                showPage((currentPageGroup - 1) * maxPagesPerGroup + 1);
            });
        }

        // Page Numbers
        for(let i = startPage; i <= endPage; i++) {
            addButton(i, () => {
                if(i === endPage && endPage < totalPages) currentPageGroup++;
                else if(i === startPage && startPage > 1) currentPageGroup--;
                showPage(i);
            });
        }

        // Next & Last Group
        if(endPage < totalPages) {
            addButton(">>", () => {
                currentPageGroup++;
                createPagination();
                showPage((currentPageGroup - 1) * maxPagesPerGroup + 1);
            });
            addButton(">|", () => {
                currentPageGroup = Math.ceil(totalPages / maxPagesPerGroup);
                createPagination();
                showPage(totalPages);
            });
        }
    }

    // Sıralama Fonksiyonu
    function sortTable(key) {
        const direction = currentSort.key === key ? 
                        (currentSort.direction === 'asc' ? 'desc' : 'asc') : 'asc';
        
        rows.sort((a, b) => {
            const aVal = a.dataset[key];
            const bVal = b.dataset[key];
            return direction === 'asc' ? 
                 aVal.localeCompare(bVal, 'tr') : 
                 bVal.localeCompare(aVal, 'tr');
        });

        currentSort = { key, direction };
        currentPageGroup = 1;
        updateSortIndicators(key, direction);
        createPagination();
        showPage(1);
    }

    // Diğer Yardımcı Fonksiyonlar
    function updateSortIndicators(key, direction) {
        document.querySelectorAll('th').forEach(th => {
            const activeHeader = document.querySelector(`th[data-sort-key="${key}"]`);
            activeHeader.querySelector('.sort-indicator').textContent = direction === 'asc' ? '↑' : '↓';
        });
    }

    // Dinamik Filtreleme ve Sayfa Yükleme
    rowsPerPageSelect.addEventListener("change", () => {
        rowsPerPage = parseInt(rowsPerPageSelect.value);
        createPagination();
        showPage(1);
    });
    
    createPagination();
    showPage(1);
    </script>
	<script>
    // Tüm mevcut JavaScript kodları burada olacak (sort, pagination vs.)

    // 1. Tıklama Özelliği Ekliyoruz
    document.getElementById('user-table-body').addEventListener('click', function(e) {
        const row = e.target.closest('tr');
        if (row) {
            // 2. Bilgileri Alıyoruz (PHP'deki data-* ile eşleşmeli)
            const params = new URLSearchParams({
                sicilno: row.dataset.sicilno, 
                name: row.dataset.name,
                surname: row.dataset.surname,
                title: row.dataset.title,
                department: row.dataset.department
            });
            
            // 3. Yeni Sayfaya Yönlendir
            window.location.href = `/index.php?r=site/profil&${params.toString()}`;
        }
    });

    // 4. Diğer Tüm Fonksiyonlar Olduğu Gibi Kalıyor
    // (sortTable, showPage, createPagination vs.)
    </script>
</body>
</html>
