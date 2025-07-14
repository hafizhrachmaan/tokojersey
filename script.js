document.addEventListener("DOMContentLoaded", function () {
  // ------------------ JERSEY MODAL ------------------
  if (typeof jerseyData !== "undefined") {
    const jerseyModal = document.getElementById("jerseyModal");

    if (jerseyModal) {
      jerseyModal.addEventListener("show.bs.modal", function (event) {
        const triggerElement = event.relatedTarget;
        const jerseyId = triggerElement.getAttribute("data-jersey-id");
        const currentJersey = jerseyData.find((j) => j.id == jerseyId);
        if (!currentJersey) return;

        // Set form value
        jerseyModal.querySelector("#modalProductId").value = currentJersey.id;
        jerseyModal.querySelector("#modalTeam").textContent = currentJersey.team;
        jerseyModal.querySelector("#modalName").textContent = currentJersey.name;
        jerseyModal.querySelector("#modalPrice").textContent = `Rp${parseInt(currentJersey.price).toLocaleString('id-ID')}`;

        jerseyModal.querySelector("#modalDescription").textContent = currentJersey.description;

        const modalMainImage = jerseyModal.querySelector("#modalMainImage");
        const modalThumbnailContainer = jerseyModal.querySelector("#modalThumbnailContainer");

        modalThumbnailContainer.innerHTML = "";
        if (currentJersey.gallery_images && currentJersey.gallery_images.length > 0) {
          modalMainImage.src = currentJersey.gallery_images[0];
          currentJersey.gallery_images.forEach((imgUrl) => {
            const thumb = document.createElement("img");
            thumb.src = imgUrl;
            thumb.className = "img-fluid img-thumbnail modal-thumbnail";
            modalThumbnailContainer.appendChild(thumb);
          });
        }

        modalThumbnailContainer.addEventListener("click", function (e) {
          if (e.target.nodeName === "IMG") {
            modalMainImage.src = e.target.src;
          }
        });

        const sizeContainer = jerseyModal.querySelector("#modalSizeOptions");
        const selectedSizeInput = jerseyModal.querySelector("#modalSelectedSize");

        sizeContainer.innerHTML = "";

        const availableSizes = currentJersey.sizes && currentJersey.sizes.length > 0
          ? currentJersey.sizes
          : ["S", "M", "L", "XL"];

        availableSizes.forEach((size) => {
          const btn = document.createElement("button");
          btn.className = "btn btn-outline-dark";
          btn.textContent = size;

          btn.addEventListener("click", function (e) {
            e.preventDefault();
            selectedSizeInput.value = size;

            sizeContainer.querySelectorAll("button").forEach(b => b.classList.remove("active"));
            btn.classList.add("active");
          });

          sizeContainer.appendChild(btn);
        });

        // ✅ [REVISI] Menambahkan interaksi tombol + dan -
const quantityInput = jerseyModal.querySelector("#modalQuantity");
const qtyPlus = jerseyModal.querySelector("#qtyPlus");     // Tombol "+"
const qtyMinus = jerseyModal.querySelector("#qtyMinus");   // Tombol "-"

if (quantityInput) {
  quantityInput.value = 1;

  if (qtyPlus && qtyMinus) {
    qtyPlus.addEventListener("click", () => {
      quantityInput.value = parseInt(quantityInput.value || 1) + 1;
    });

    qtyMinus.addEventListener("click", () => {
      const current = parseInt(quantityInput.value || 1);
      if (current > 1) quantityInput.value = current - 1;
    });
  }
}


        const jerseyCartForm = jerseyModal.querySelector("#jerseyCartForm");
        if (jerseyCartForm) {
          jerseyCartForm.addEventListener("submit", function (e) {
            if (!selectedSizeInput.value) {
              e.preventDefault();
              alert("Silakan pilih ukuran terlebih dahulu sebelum menambahkan ke keranjang.");
            }
          });
        }
      });
    }

    const teamSelect = document.getElementById("filterTeam");
    const sizeSelect = document.getElementById("filterSize");
    const priceSelect = document.getElementById("filterPrice");
    const applyBtn = document.getElementById("applyFiltersBtn");
    const productGrid = document.querySelector(".row.row-cols-1.row-cols-sm-2.row-cols-lg-4.g-4");

    if (applyBtn && productGrid) {
      applyBtn.addEventListener("click", function () {
        const selectedTeam = teamSelect.value;
        const selectedSize = sizeSelect.value;
        const selectedPrice = priceSelect.value;

        let filtered = [...jerseyData];

        if (selectedTeam !== "") {
          filtered = filtered.filter(j => j.team === selectedTeam);
        }

        if (selectedSize !== "") {
          filtered = filtered.filter(j => j.sizes.includes(selectedSize));
        }

        if (selectedPrice !== "") {
          filtered = filtered.filter(j => {
            const price = parseFloat(j.price);
            if (selectedPrice === "Under $50") return price < 50;
            if (selectedPrice === "$50 - $100") return price >= 50 && price <= 100;
            if (selectedPrice === "$100 - $150") return price > 100 && price <= 150;
            if (selectedPrice === "Over $150") return price > 150;
            return true;
          });
        }

        productGrid.innerHTML = "";
        filtered.forEach(jersey => {
          const col = document.createElement("div");
          col.className = "col";
          col.innerHTML = `
            <div class="card h-100 shadow-sm product-card" data-bs-toggle="modal" data-bs-target="#jerseyModal" data-jersey-id="${jersey.id}">
              <div class="position-relative">
                <img src="${jersey.image}" class="card-img-top" alt="${jersey.alt}">
                <button class="btn btn-light position-absolute top-0 end-0 m-2 rounded-circle p-1 favorite-btn">
                  <i class="bi bi-heart"></i>
                </button>
              </div>
              <div class="card-body">
                <h5 class="card-title fw-semibold">${jersey.team}</h5>
                <p class="card-text text-muted">${jersey.name}</p>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="fw-bold text-primary fs-5">Rp${parseInt(jersey.price).toLocaleString('id-ID')}</span>

                  <button class="btn btn-primary rounded-circle"><i class="bi bi-cart"></i></button>
                </div>
              </div>
            </div>`;
          productGrid.appendChild(col);
        });
      });
    }
  }



  

  // ------------------ ACCESSORY MODAL ------------------
  if (typeof accessoryData !== "undefined") {
    const accessoryModal = document.getElementById("accessoryModal");

    if (accessoryModal) {
      accessoryModal.addEventListener("show.bs.modal", function (event) {
        const triggerElement = event.relatedTarget;
        const accessoryId = triggerElement.getAttribute("data-accessory-id");
        const currentAccessory = accessoryData.find((a) => a.id == accessoryId);
        if (!currentAccessory) return;
        const accessoryCartForm = accessoryModal.querySelector("#accessoryCartForm");
const accessoryQuantityInput = accessoryModal.querySelector("#quantityInput");
const accessoryHiddenQuantity = accessoryModal.querySelector("#accessoryQuantity");
const accessoryHiddenId = accessoryModal.querySelector("#accessoryModalId");

if (accessoryQuantityInput) {
  accessoryQuantityInput.value = 1;
  accessoryHiddenQuantity.value = 1;
}
accessoryHiddenId.value = currentAccessory.id;

const incrementBtn = accessoryModal.querySelector("#incrementQuantity");
const decrementBtn = accessoryModal.querySelector("#decrementQuantity");

if (incrementBtn && decrementBtn) {
  incrementBtn.addEventListener("click", () => {
    accessoryQuantityInput.value = parseInt(accessoryQuantityInput.value) + 1;
    accessoryHiddenQuantity.value = accessoryQuantityInput.value;
  });

  decrementBtn.addEventListener("click", () => {
    if (parseInt(accessoryQuantityInput.value) > 1) {
      accessoryQuantityInput.value = parseInt(accessoryQuantityInput.value) - 1;
      accessoryHiddenQuantity.value = accessoryQuantityInput.value;
    }
  });
}

accessoryQuantityInput.addEventListener("input", () => {
  accessoryHiddenQuantity.value = accessoryQuantityInput.value;
});

        accessoryModal.querySelector("#accessoryModalCategory").textContent = currentAccessory.category;
        accessoryModal.querySelector("#accessoryModalName").textContent = currentAccessory.name;
        accessoryModal.querySelector("#accessoryModalPrice").textContent = `Rp${parseFloat(currentAccessory.price).toLocaleString('id-ID')}`;


        const modalMainImage = accessoryModal.querySelector("#accessoryModalMainImage");
        const modalThumbnailContainer = accessoryModal.querySelector("#accessoryModalThumbnailContainer");

        modalThumbnailContainer.innerHTML = "";
        if (currentAccessory.gallery_images && currentAccessory.gallery_images.length > 0) {
          modalMainImage.src = currentAccessory.gallery_images[0];
          currentAccessory.gallery_images.forEach((imageUrl) => {
            const thumb = document.createElement("img");
            thumb.src = imageUrl;
            thumb.className = "img-fluid img-thumbnail modal-thumbnail mb-2";
            thumb.style.cursor = "pointer";
            thumb.addEventListener("click", () => {
              modalMainImage.src = imageUrl;
            });
            modalThumbnailContainer.appendChild(thumb);
          });
        } else {
          modalMainImage.src = currentAccessory.image;
        }

        const colorContainer = accessoryModal.querySelector("#accessoryColorOptions");
        colorContainer.innerHTML = "";
        if (currentAccessory.colors && currentAccessory.colors.length > 0) {
          currentAccessory.colors.forEach(color => {
            const btn = document.createElement("button");
            btn.className = "btn btn-outline-dark me-2";
            btn.textContent = color;
            colorContainer.appendChild(btn);
          });
        }
      });
      

      // Tambahkan tombol Add to Cart handler
const addToCartBtn = accessoryModal.querySelector(".btn.btn-primary.flex-grow-1");
if (addToCartBtn) {
  addToCartBtn.addEventListener("click", function () {
    const name = accessoryModal.querySelector("#accessoryModalName").textContent.trim();
    const priceText = accessoryModal.querySelector("#accessoryModalPrice").textContent.trim().replace(/[^\d]/g, '');

    const price = parseInt(priceText);
    const quantity = parseInt(accessoryModal.querySelector("#quantityInput").value);

    if (isNaN(quantity) || quantity <= 0) {
      alert("Silakan masukkan jumlah yang valid.");
      return;
    }

    const total = price * quantity;

    console.log("Ditambahkan ke keranjang:", {
      name,
      price,
      quantity,
      total
    });

    alert(`${quantity}x ${name} berhasil ditambahkan ke keranjang.`);
  });
}

      const categorySelect = document.getElementById("filterCategory");
      const priceSelect = document.getElementById("filterPrice");
      const sortSelect = document.getElementById("filterSort");
      const applyBtn = document.querySelector(".btn.btn-primary.w-100");
      const productContainer = document.querySelector(".row.row-cols-1");

      if (applyBtn && productContainer) {
        applyBtn.addEventListener("click", function () {
          const selectedCategory = categorySelect.value;
          const selectedPrice = priceSelect.value;
          const selectedSort = sortSelect.value;

          let filtered = [...accessoryData];

          if (selectedCategory !== "") {
            filtered = filtered.filter(item => item.category === selectedCategory);
          }

          if (selectedPrice !== "") {
            filtered = filtered.filter(item => {
              const price = parseFloat(item.price);
              if (selectedPrice === "Di bawah $20") return price < 20;
              if (selectedPrice === "$20 - $30") return price >= 20 && price <= 30;
              if (selectedPrice === "$30 - $50") return price > 30 && price <= 50;
              if (selectedPrice === "Di atas $50") return price > 50;
              return true;
            });
          }

          if (selectedSort === "lowToHigh") {
            filtered.sort((a, b) => a.price - b.price);
          } else if (selectedSort === "highToLow") {
            filtered.sort((a, b) => b.price - a.price);
          } else if (selectedSort === "newest") {
            filtered.sort((a, b) => b.id - a.id);
          }

          productContainer.innerHTML = "";
          filtered.forEach(accessory => {
            const col = document.createElement("div");
            col.className = "col";
            col.innerHTML = `
              <div class="card h-100 shadow-sm product-card" data-bs-toggle="modal" data-bs-target="#accessoryModal" data-accessory-id="${accessory.id}">
                <div class="position-relative">
                  <img src="${accessory.image}" class="card-img-top" alt="${accessory.name}" />
                  <button class="btn btn-light position-absolute top-0 end-0 m-2 rounded-circle p-1 favorite-btn">
                    <i class="bi bi-heart"></i>
                  </button>
                </div>
                <div class="card-body">
                  <span class="badge bg-light text-dark mb-2">${accessory.category}</span>
                  <h5 class="card-title fw-semibold">${accessory.name}</h5>
                  <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-primary fs-5">Rp${parseInt(accessory.price).toLocaleString('id-ID')}</span>

                    <button class="btn btn-primary rounded-circle"><i class="bi bi-cart"></i></button>
                  </div>
                </div>
              </div>`;
            productContainer.appendChild(col);
          });
        });
      }
    }
  }

  // Tambahan khusus untuk halaman search.php
document.querySelectorAll(".openJerseyModal").forEach(button => {
  button.addEventListener("click", function () {
    const jerseyId = this.getAttribute("data-id");
    const currentJersey = jerseyData.find(j => j.id == jerseyId);
    if (!currentJersey) return;

    const jerseyModal = document.getElementById("jerseyModal");
    if (!jerseyModal) return;

    const modal = new bootstrap.Modal(jerseyModal);

    jerseyModal.querySelector("#modalProductId").value = currentJersey.id;
    jerseyModal.querySelector("#modalTeam").textContent = currentJersey.team;
    jerseyModal.querySelector("#modalName").textContent = currentJersey.name;
    jerseyModal.querySelector("#modalPrice").textContent = `Rp${parseInt(currentJersey.price).toLocaleString('id-ID')}`;

    jerseyModal.querySelector("#modalDescription").textContent = currentJersey.description;

    

    const modalMainImage = jerseyModal.querySelector("#modalMainImage");
    const modalThumbnailContainer = jerseyModal.querySelector("#modalThumbnailContainer");
    modalThumbnailContainer.innerHTML = "";

    if (currentJersey.gallery_images?.length > 0) {
      modalMainImage.src = currentJersey.gallery_images[0];
      currentJersey.gallery_images.forEach(imgUrl => {
        const thumb = document.createElement("img");
        thumb.src = imgUrl;
        thumb.className = "img-fluid img-thumbnail modal-thumbnail";
        modalThumbnailContainer.appendChild(thumb);
      });
    }

    const sizeContainer = jerseyModal.querySelector("#modalSizeOptions");
    const selectedSizeInput = jerseyModal.querySelector("#modalSelectedSize");
    sizeContainer.innerHTML = "";

    const sizes = currentJersey.sizes?.length > 0 ? currentJersey.sizes : ["S", "M", "L", "XL"];
    sizes.forEach(size => {
      const btn = document.createElement("button");
      btn.className = "btn btn-outline-dark";
      btn.textContent = size;
      btn.addEventListener("click", function (e) {
        e.preventDefault();
        selectedSizeInput.value = size;
        sizeContainer.querySelectorAll("button").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
      });
      sizeContainer.appendChild(btn);
    });

    jerseyModal.querySelector("#modalQuantity").value = 1;
    modal.show();
  });
});

  document.querySelectorAll('input[name="quantity"]').forEach((input) => {
    input.addEventListener("change", function () {
      const form = input.closest("form");
      if (!form) return;

      const formData = new FormData(form);
      fetch("update_jumlah.php", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.text())
        .then(() => {
          window.location.reload();
        })
        .catch((err) => {
          console.error("Gagal update jumlah:", err);
        });
    });
  });

  fetch("cart_count.php")
    .then(response => response.text())
    .then(count => {
      const badge = document.getElementById("cart-count-badge");
      if (badge) badge.textContent = count;
    })
    .catch(err => {
      console.error("Gagal ambil jumlah cart:", err);
    });
});



