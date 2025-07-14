
<div class="modal fade" id="jerseyModal" tabindex="-1" aria-labelledby="jerseyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header border-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6 mb-4 mb-md-0">
            <div class="row">
              <div class="col-2">
                <div id="modalThumbnailContainer" class="d-flex flex-column gap-2">
                </div>
              </div>
              <div class="col-10">
                <img id="modalMainImage" src="https://placehold.co/600x600/eee/ccc?text=Jersey" class="img-fluid rounded" alt="Jersey Preview">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <h2 id="modalTeam" class="fw-bold mb-1">Team Name</h2>
            <h3 id="modalName" class="text-muted mb-4">Jersey Name</h3>

            <p id="modalPrice" class="fs-4 fw-bold text-primary mb-4">Rp00.000</p>

            <p id="modalDescription" class="text-muted mb-4">Deskripsi jersey akan ditampilkan di sini.</p>

            <div class="mb-4">
              <h4 class="fw-semibold mb-2">Ukuran</h4>
              <div id="modalSizeOptions" class="btn-group size-selector" role="group">
              </div>
            </div>

            <form method="POST" action="add_to_cart.php" class="d-flex flex-column gap-3 p-3" id="jerseyCartForm">
              <input type="hidden" name="product_id" id="modalProductId">
              <input type="hidden" name="product_type" value="jersey">
              <input type="hidden" name="size" id="modalSelectedSize">

<div>
  <label for="modalQuantity" class="form-label">Jumlah:</label>
  <div class="input-group" style="width: 140px;">
    <button type="button" class="btn btn-outline-secondary" id="qtyMinus">-</button>
    <input type="number" id="modalQuantity" name="quantity" value="1" min="1" class="form-control text-center" required>
    <button type="button" class="btn btn-outline-secondary" id="qtyPlus">+</button>
  </div>
</div>




              <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-2">
                  <i class="bi bi-cart"></i> Masukkan Keranjang
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
