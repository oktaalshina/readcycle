console.log("timeline.js loaded")
const bookList = document.getElementById("book-list");
const pagination = document.getElementById("pagination");
const searchInput = document.getElementById("searchInput");
const genreFilter = document.getElementById("genreFilter");
const provinceFilter = document.getElementById("provinceFilter");

const ITEMS_PER_PAGE = 5;
let currentPage = 1;

// === INIT FILTER OPTION ===
function initFilters() {
    const genres = [...new Set(booksData.map(b => b.genreName).filter(Boolean))];
    const provinces = [...new Set(booksData.map(b => b.provinceName).filter(Boolean))];

    genres.forEach(g => {
        genreFilter.innerHTML += `<option value="${g}">${g}</option>`;
    });
    provinces.forEach(p => {
        provinceFilter.innerHTML += `<option value="${p}">${p}</option>`;
    });
}

// === RENDER BOOKS ===
function renderBooks() {
    bookList.innerHTML = "";

    let filtered = booksData.filter(b =>
        b.title.toLowerCase().includes(searchInput.value.toLowerCase()) ||
        b.author.toLowerCase().includes(searchInput.value.toLowerCase())
    );

    if (genreFilter.value !== "") {
        filtered = filtered.filter(b => b.genreName === genreFilter.value);
    }

    if (provinceFilter.value !== "") {
        filtered = filtered.filter(b => b.provinceName === provinceFilter.value);
    }

    const start = (currentPage - 1) * ITEMS_PER_PAGE;
    const pageItems = filtered.slice(start, start + ITEMS_PER_PAGE);

    pageItems.forEach(book => {
        bookList.innerHTML += `
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2 align-item-center">
                        <h6 class="text-muted fw-bold">@${book.username}</h6>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge bg-transparent text-dark border">#${book.genreName}</span>
                            <span class="badge bg-transparent text-dark border">${book.provinceName}</span>
                            <span class="badge ${book.isAvailable ? 'bg-success' : 'bg-secondary'}">
                                ${book.isAvailable ? 'Available' : 'Not Available'}
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 text-center">
                            <img src="${book.imageUrl}" class="img-fluid rounded" style="height:200px;object-fit:cover;"></img>
                        </div>

                        <div class="col-md-9">
                            <p><strong>Judul: </strong> ${book.title}</p>
                            <p><strong>Penulis: </strong> ${book.author}</p>
                            <p><strong>Kondisi: </strong> ${book.bookCondition}</p>
                            <p><strong>Ingin ditukar: </strong> ${book.lookingFor}</p>
                            <p><strong>Deskripsi: </strong><br></br>
                            ${book.description}</p>
                        </div>
                    </div>

                    <hr>

                    <button class="btn btn-sm btn-outline-secondary" onclick="toggleComments(${book.id})">
                        Lihat Komentar (${book.comments.length})
                    </button>

                    <div id="comments-${book.id}" class="mt-3 d-none">
                        ${
                            book.comments.length === 0
                            ? `<p class="small text-muted fst-italic">Belum ada komentar.</p>`
                            : book.comments.map(c => `
                                <div class="p-2 mb-2 comment-box rounded">
                                    <strong>@${c.username}</strong> ${c.text}
                                </div>
                            `).join("")
                        }

                        <div class="d-flex gap-2 mt-2">
                            <input class="form-control form-control-sm" placeholder="Tulis Komentar..." disabled></input>
                            <button class="btn btn-deco btn-sm" onclick="unavailable()">Kirim</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    renderPagination(filtered.length);
}

// === COMMENTS TOGGLE ===
function toggleComments(id) {
    const el = document.getElementById(`comments-${id}`);
    el.classList.toggle("d-none");
}

// === PAGINATION ===
function renderPagination(totalItems) {
    pagination.innerHTML = "";
    const totalPages = Math.ceil(totalItems / ITEMS_PER_PAGE);
    
    for (let i = 1; i <= totalPages; i++) {
        pagination.innerHTML += `
        <li class="page-item ${i === currentPage ? 'active' : ''}">
            <button class="page-link" onclick="goPage(${i})">${i}</button>
        </li>
        `;
    }
}

function goPage(page) {
    console.log("PINDAH KE PAGE:", page);
    currentPage = page;
    renderBooks();
}

// === EVENT ===
[searchInput, genreFilter, provinceFilter].forEach(el => {
    el.addEventListener("input", () => {
        currentPage = 1;
        renderBooks();
    });
});

// INIT
initFilters();
renderBooks();