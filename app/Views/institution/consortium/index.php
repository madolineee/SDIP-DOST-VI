<?= $this->extend('layouts/header-layout') ?>
<?= $this->section('content') ?>

<style>
    .buttons-container {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-bottom: 10px;
        margin-top: -10px;
    }

    .title {
        font-size: 2.2rem;
        margin-top: 10px;
        margin-bottom: 1px;
    }

    .box {
        margin-top: 30px;
    }
</style>

<div class="container">
    <div class="box mt-4">

        <div class="title">
            <h1 class="title has-text-centered">DOST VI Consortium</h1>
        </div>

        <!-- Buttons beside tabs -->
        <div class="buttons-container">
            <a href="<?= base_url('institution/consortium/create') ?>" class="button is-primary">
                <span class="icon"><i class="fas fa-plus"></i></span>
                <span>Create New</span>
            </a>

            <button class="button is-light">
                <span class="icon"><i class="fas fa-download"></i></span>
                <span>Download Template</span>
            </button>
        </div>

        <!-- Table Section -->
        <div class="table-container">
            <table class="table is-striped is-hoverable is-fullwidth">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Institution</th>
                        <th class="has-text-centered">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($consortiums)): ?>
                        <?php foreach ($consortiums as $consortium): ?>
                            <tr>
                                <td><?= esc($consortium->consortium_name ?? 'N/A') ?></td>
                                <td><?= esc($consortium->institution_name ?? '') ?></td>
                                <td class="has-text-centered">
                                    <div class="buttons is-flex is-justify-content-center is-align-items-center"
                                        style="gap: 10px;">
                                        <a href="<?= site_url('/institution/consortium/edit/' . $consortium->consortium_id) ?>"
                                            class="button is-info is-small"
                                            style="margin-left: 8px; min-width: 120px; display: flex; justify-content: center; align-items: center;">
                                            <span class="icon"><i class="fas fa-edit"></i></span>
                                            <sqpan>Edit</sqpan>
                                        </a>
                                        <a href="<?= site_url('institution/consortium/delete/' . $consortium->consortium_id); ?>"
                                            class="button is-danger is-small"
                                            style="min-width: 120px; display: flex; justify-content: center; align-items: center;"
                                            onclick="return confirm('Are you sure you want to delete this regional office?');">
                                            <span class="icon"><i class="fas fa-trash"></i></span>
                                            <span>Delete</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="has-text-centered has-text-grey-light">
                                No regional offices found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this scientist?")) {
            window.location.href = "<?= base_url('institution/consortium/delete/') ?>" + id;
        }
    }
</script>
</body>

<?= $this->endSection() ?>