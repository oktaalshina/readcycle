console.log("posting.js loaded")

// === DUMMY DATA ===
const genres = [
    "Fiksi",
    "Non-Fiksi",
    "Novel",
    "Edukasi",
    "Komik"
];

const provinces = [
    "DI Yogyakarta",
    "DKI Jakarta",
    "Jawa Tengah",
    "Jawa Timur",
    "Jawa Barat",
    "Bali"
];

// === INIT DROPDOWN ===
const genreSelect = document.getElementById("genreSelect");
const provinceSelect = document.getElementById("provinceSelect");

genres.forEach(g => {
    genreSelect.innerHTML += `<option value="${g}">${g}</option>`;
});

provinces.forEach(p => {
    provinceSelect.innerHTML += `<option value="${p}">${p}</option>`
});

// === IMAGE PREVIEW ===
const imageInput = document.getElementById("imageInput");
const preview = document.getElementById("preview");

imageInput.addEventListener("change", e => {
    const file = e.target.file[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = () => {
        preview.innerHTML = `<img src="${reader.result}" class="preview-image">`;
    };
    reader.readAsDataURL(file);
});

// === SUBMIT ===
document.getElementById(postingForm).addEventListener("submit", e => {
    e.preventDefault();
    alert("Fitur tidak tersedia pada live demo.");
});