<div
  class="modal fade"
  id="accessoryModal"
  tabindex="-1"
  aria-labelledby="accessoryModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header border-0">
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6 mb-4 mb-md-0">
            <div class="row">
              <div class="col-2">
                <div id="accessoryModalThumbnailContainer" class="d-flex flex-column gap-2"></div>
              </div>
              <div class="col-10">
                <img id="accessoryModalMainImage" src="https://placehold.co/600x600/eee/ccc?text=Accessory" class="img-fluid rounded" alt="Accessory Preview">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <span id="accessoryModalCategory" class="badge bg-light text-dark mb-2">Kategori</span>
            <h2 id="accessoryModalName" class="fw-bold mb-4">Nama Aksesori</h2>
            <p id="accessoryModalPrice" class="fs-4 fw-bold text-primary mb-4">Rp0</p>
            <p id="accessoryModalDescription" class="text-muted mb-4">
              A collection of stylish accessories that combine comfort and functionality.
            </p>

            <div class="mb-4">
              <h4 class="fw-semibold mb-2">Warna</h4>
              <div id="accessoryColorOptions" class="btn-group color-selector" role="group"></div>
            </div>

            <div class="mb-4">
              <h4 class="fw-semibold mb-2">Jumlah</h4>
              <div class="input-group" style="width: 140px">
                <button class="btn btn-outline-secondary" type="button" id="decrementQuantity">-</button>
                <input type="number" class="form-control text-center" value="1" min="1" id="quantityInput" />
                <button class="btn btn-outline-secondary" type="button" id="incrementQuantity">+</button>
              </div>
            </div>

            <form action="add_to_cart.php" method="POST" id="accessoryCartForm" class="w-100">
              <input type="hidden" name="product_id" id="accessoryModalId">
              <input type="hidden" name="product_type" value="accessory">
              <input type="hidden" name="size" value="">
              <input type="hidden" name="quantity" id="accessoryQuantity">
              <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-2">
                  <i class="bi bi-cart"></i> Tambah ke Keranjang
                </button>
                <button type="button" class="btn btn-outline-secondary">
                  <i class="bi bi-heart"></i>
                </button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
