<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">Số tiền đã chia</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body py-10 px-lg-17">
        <div class="scroll-y me-n7 pe-7" style="max-height: calc(100vh - 30rem)">

            @foreach ($money as $m)
                <div class="fv-row mb-7">
                    <input type="hidden" class="money-id" value="{{ $m->id }}" />
                    <div class="input-group mb-5">
                        <span class="input-group-text min-w-175px" id="money-{{ $m->id }}">
                            Số tiền:
                            <?= number_format($m->money, 0, ',', ',') ?>
                        </span>
                        <input type="text" class="form-control" placeholder="Ghi chú" value="{{ $m->note }}"
                            aria-label="Ghi chú" aria-describedby="money-{{ $m->id }}" readonly disabled />
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="modal-footer flex-center">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
    </div>
</div>
