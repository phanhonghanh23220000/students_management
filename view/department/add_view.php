<?php
if (!defined('ROOT_PATH')) {
    die('Can not access');
}
$titlePage = "BTEC - Create Department";
$errorAdd = $_SESSION['error_add_department'] ?? null;
?>
<?php require 'view/partials/header_view.php'; ?>

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-home"></i>
        </span> Create Department
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>
<div class="row">
    <div class="col-sm-12 col-md-12">
        <a class="btn btn-primary" href="index.php?c=departments&m=index">List Department</a>
        <div class="card card-primary mt-3">
            <div class="card-header bg-primary">
                <h5 class="card-title text-white">Add New Department</h5>
            </div>
            <div class="card-body">
                <form enctype="multipart/form-data" method="post" action="index.php?c=departments&m=handle-add">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group mb-3">
                                <label>Name</label>
                                <input class="form-control" type="text" name="name" />
                                <?php if (!empty($errorAdd['name'])) : ?>
                                    <?php if (is_array($errorAdd['name'])) : ?>
                                        <span class="text-danger"><?= $errorAdd['name'][0]; ?></span>
                                    <?php else : ?>
                                        <span class="text-danger"><?= $errorAdd['name']; ?></span>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <?php if (isset($_POST['btnSave'])) : ?>
                                        <span class="text-danger">Please enter the name.</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <div class="form-group mb-3">
                                <label>Name's Leader</label>
                                <input class="form-control" type="text" name="leader" />
                                <?php if (!empty($errorAdd['leader'])) : ?>
                                    <?php if (is_array($errorAdd['leader'])) : ?>
                                        <span class="text-danger"><?= $errorAdd['leader'][0]; ?></span>
                                    <?php else : ?>
                                        <span class="text-danger"><?= $errorAdd['leader']; ?></span>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <?php if (isset($_POST['btnSave'])) : ?>
                                        <span class="text-danger">Please enter the leader's name.</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <div class="form-group mb-3">
                                <label>Logo</label>
                                <input type="file" class="form-control" name="logo" />
                                <?php if (!empty($errorAdd['logo'])) : ?>
                                    <?php if (is_array($errorAdd['logo'])) : ?>
                                        <span class="text-danger"><?= $errorAdd['logo'][0]; ?></span>
                                    <?php else : ?>
                                        <span class="text-danger"><?= $errorAdd['logo']; ?></span>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <?php if (isset($_POST['btnSave'])) : ?>
                                        <span class="text-danger">Please upload a logo.</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group mb-3">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="1">Active</option>
                                    <option value="0">Deactive</option>
                                </select>
                            </div>
                            <!-- CHOOSE date  -->
                            <div class="form-group mb-3">
                                <label>Beginning Date</label>
                                <input type="date" class="form-control" name="beginning_date" max="<?php echo date('Y-m-d'); ?>">
                                <?php if (!empty($errorAdd['beginning_date'])) : ?>
                                    <?php if (is_array($errorAdd['beginning_date'])) : ?>
                                        <span class="text-danger"><?= $errorAdd['beginning_date'][0]; ?></span>
                                    <?php else : ?>
                                        <span class="text-danger"><?= $errorAdd['beginning_date']; ?></span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <!-- CHOOSE date -->
                                <button class="btn btn-primary" type="submit" name="btnSave">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'view/partials/footer_view.php'; ?>