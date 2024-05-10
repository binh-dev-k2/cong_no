<div id="drawer_remind" class="bg-white" data-kt-drawer="true" data-kt-drawer-activate="true"
    data-kt-drawer-toggle="#drawer_remind_button" data-kt-drawer-close="#drawer_remind_close"
    data-kt-drawer-width="500px">

    <div class="card rounded-0 w-100">
        <div class="card-header pe-5">
            <div class="card-title">
                Lịch sử nhắc nhở
            </div>
            <div class="card-toolbar">
            </div>
        </div>
        <div class="card-body hover-scroll-overlay-y">
            <div class="timeline-label">

                {{-- <div class="timeline-item">
                    <div class="timeline-label"></div>
                    <div class="timeline-badge">
                        <i class="fa fa-genderless text-primary fs-1"></i>
                    </div>
                    <div class="fw-mormal timeline-content text-muted ps-3">
                        <div class="fw-bold fs-6 text-gray-800">
                            {{ $history->type_update == 1 ? $history->getUserUpdate->email : 'User' }}</div>
                        {{ \Carbon\Carbon::parse($history->created_at)->format('H:i d/m/Y') }}
                    </div>
                </div> --}}

            </div>
        </div>
    </div>
</div>
