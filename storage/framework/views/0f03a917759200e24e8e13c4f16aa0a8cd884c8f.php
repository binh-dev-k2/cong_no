<div id="kt_drawer_example_basic" class="bg-white" data-kt-drawer="true" data-kt-drawer-activate="true"
    data-kt-drawer-toggle="#kt_drawer_example_basic_button" data-kt-drawer-close="#kt_drawer_example_basic_close"
    data-kt-drawer-width="500px">
    <!--begin::Card-->
    <div class="card rounded-0 w-100">
        <!--begin::Card header-->
        <div class="card-header pe-5">
            <!--begin::Title-->
            <div class="card-title">
                Lịch sử chỉnh sửa
            </div>
            <!--end::Title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">

                <!--end::Close-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body hover-scroll-overlay-y">

            <!--begin::Timeline-->
            <div class="timeline-label">

                <?php $__currentLoopData = $studentProfile->getHistory->reverse()->take(20); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <!--begin::Item-->
                    <div class="timeline-item">
                        <!--begin::Label-->
                        <div class="timeline-label"></div>
                        <!--end::Label-->

                        <!--begin::Badge-->
                        <div class="timeline-badge">
                            <i
                                class="fa fa-genderless <?php if($history->type_update == 1): ?> text-primary <?php else: ?> text-warning <?php endif; ?> fs-1"></i>
                        </div>
                        <!--end::Badge-->

                        <!--begin::Text-->
                        <div class="fw-mormal timeline-content text-muted ps-3">
                            <div class="fw-bold fs-6 text-gray-800">
                                <?php echo e($history->type_update == 1 ? $history->getUserUpdate->email : 'User'); ?></div>
                            <?php echo e(\Carbon\Carbon::parse($history->created_at)->format('H:i d/m/Y')); ?>

                            <ul class="fs-6 text-gray-800">

                                <?php $__currentLoopData = json_decode($history->information_update); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>
                                        <?php echo e($item->label); ?> : <?php echo e($item->old); ?> =>
                                        <?php echo e($item->new); ?>

                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                        <!--end::Text-->
                    </div>
                    <!--end::Item-->
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


            </div>
            <!--end::Timeline-->

        </div>
        <!--end::Card body-->
    </div>
</div>
<?php /**PATH D:\CodeTools\laragon\www\WORK\quan_ly_cong_no\resources\views/customer/components/history.blade.php ENDPATH**/ ?>